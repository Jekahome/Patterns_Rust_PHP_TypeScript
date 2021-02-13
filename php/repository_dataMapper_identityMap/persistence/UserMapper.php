<?php
namespace persistence;
use model\IUser;


class UserMapper extends AbstractMapper implements UserMapperInterface
{

    public function __construct(\PDO $db)
    {
        parent::__construct($db);
    }

    /**
     * Вынести зависимость datamapper'a от базы данный !!!
     * Что делат:
     * 1. Возвращает искомый обьект с identitymap
     * 2. Иначе находит его в базе и все его зависимости и сохраняет в identity
     * 3. Важно прежде чем загружать зависимости, загрузить обьект в IdentityMap, чтобы избежать зацикливания
     * @param integer $id
     * @return \model\User
     * @throws OutOfBoundsException
     */
    public function find($id):IUser
    {

        if (true === $this->identityMap->hasId($id,\model\User::class)) {
            return $this->identityMap->getObject($id,\model\User::class);
        }

        $sth = $this->db->prepare(
            'SELECT * FROM tbl_user WHERE id = :id'
        );

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,
            'model\User', array('nickname', 'password'));
        $sth->execute();
        // let pdo fetch the User instance for you.
        $user = $sth->fetch();
        if (empty($user)) {
            throw new \OutOfBoundsException(
                sprintf('No user with id #%d exists.', $id)
            );
        }
        $this->identityMap->attach($id, $user);
        $user = $this->identityMap->getObject($id,\model\User::class);

        // load all user's articles
        $articleMapper = new ArticleMapper($this->db);
        try {
            $articles = $articleMapper->findByUserId($id);
            $user->setArticles($articles);
        } catch (\OutOfBoundsException $e) {
            // no articles at the database.
            throw $e;
        }

        return $user;
    }

    public function fetchAll():array
    {
        $sth = $this->db->query(
            'SELECT * FROM tbl_user'
        );
        $sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,
            'model\UserDTO', array('nickname', 'password'));
        $users = $sth->fetchAll();

        $entityUsers = [];
        foreach ($users as $user){
          $entityUser = $this->find($user->id);
          array_push($entityUsers,$entityUser);
        }

        return $entityUsers;
    }


    /**
     * Что делает:
     * 1.Проверяет уникальность обьекта в identitymap
     * 2.Вставка в базу пользователя
     * 3.Добавление к пользователю артиклей (которых у него нет)
     *       и добавление артиклей в ArticleMap для атриклей
     * 4.Добавления пользователя в indentitymap
     *
     * @param \model\UserDTO $user
     * @throws MapperException
     * @return \model\User
     */
    public function insert(\model\UserDTO $user):IUser
    {
        $sth = $this->db->prepare(
            "INSERT INTO tbl_user (nickname, `password`) " .
            "VALUES (:nick, :passwd)"
        );

        $sth->bindValue(':nick', $user->nickname, \PDO::PARAM_STR);
        $sth->bindValue(':passwd', $user->password, \PDO::PARAM_STR);
        $sth->execute();

        $id = (int)$this->db->lastInsertId();
        // load user and attach
        return $this->find($id);
    }

    /**
     * @param IUser $user
     * @throws MapperException
     * @return boolean
     */
    public function update(IUser $user)
    {
        if (false === $this->identityMap->contains($user)) {
            throw new MapperException('Object has no ID, cannot update.');
        }

        $sth = $this->db->prepare(
            "UPDATE tbl_user " .
            "SET nickname = :nick, `password` = :passwd WHERE id = :id"
        );

        $sth->bindValue(':nick', $user->getNickname(), \PDO::PARAM_STR);
        $sth->bindValue(':passwd', $user->getPassword(), \PDO::PARAM_STR);
        $sth->bindValue(':id', $this->identityMap->getId($user), \PDO::PARAM_INT);

        $sth->execute();

        return $sth->execute();
    }

    /**
     * @param IUser $user
     * @throws MapperException
     * @return boolean
     */
    public function delete(IUser $user)
    {
        if (false === $this->identityMap->contains($user)) {
            throw new MapperException('Object has no ID, cannot delete.');
        }

        $sth = $this->db->prepare(
            "DELETE FROM tbl_user WHERE id = :id;"
        );

        $sth->bindValue(':id', $this->identityMap->getId($user), \PDO::PARAM_INT);
        if($sth->execute()){
            $articles = $user->getArticles();

            $articleMapper = new ArticleMapper($this->db);
            foreach ($articles as $article){
                $article->detachUser($user);
                $articleMapper->identityMap->detach($article);
                //
            }
            $this->identityMap->detach($user);
            return true;
        }
        return false;
    }

}
