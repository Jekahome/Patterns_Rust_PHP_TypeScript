<?php
namespace persistence;
use model\ArticleDTO;
use model\IArticle;

class ArticleMapper extends AbstractMapper
{
    /**
     * Что делат:
     * 1. Возвращает искомый обьект с identitymap
     * 2. Иначе находит его в базе и все его зависимости и сохраняет в identity
     * 3. Важно прежде чем загружать зависимости, загрузить обьект в IdentityMap, чтобы избежать зацикливания
     * @param integer $id
     * @return \model\Article|object
     * @throws OutOfBoundsException
     */
    public function find($id):IArticle
    {
        if (true === $this->identityMap->hasId($id,\model\Article::class)) {
            return $this->identityMap->getObject($id,\model\Article::class);
        }

        $sth = $this->db->prepare(
            'SELECT * FROM tbl_article WHERE id = :id'
        );

        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,
            'model\Article', array('title', 'content'));
        $sth->execute();
        // let pdo fetch the Article instance for you.
        $article = $sth->fetch();
        if (empty($article)) {
            throw new \OutOfBoundsException(
                sprintf('No article with id #%d exists.', $id)
            );
        }

        $this->identityMap->attach($id, $article);
        $article = $this->identityMap->getObject($id,\model\Article::class);

        // load all user's articles
       $userMapper = new UserMapper($this->db);
        try {
            $article->setUser($userMapper->find($article->userId));
        } catch (\OutOfBoundsException $e) {
            // no articles at the database.
        }

        return  $article;
    }



    /**
     * @param integer $id
     * @throws OutOfBoundsException
     * @return array A list of IArticle objects.
     */
    public function findByUserId($id)
    {
        $sth = $this->db->prepare(
            "SELECT * FROM tbl_article WHERE userId = :userId"
        );

        $sth->bindValue(':userId', $id, \PDO::PARAM_INT);
        $sth->setFetchMode(\PDO::FETCH_CLASS|\PDO::FETCH_PROPS_LATE,
            'model\ArticleDTO', array('title', 'content'));
        $sth->execute();

        $articlesDTO = $sth->fetchAll();
        if (empty($articlesDTO)) {
          return [];
        }

        $buffArticles = [];
        foreach ($articlesDTO as $article){
            array_push($buffArticles, $this->find($article->id));
        }
        return $buffArticles;
    }

    /**
     * @param ArticleDTO $article
     * @throws MapperException
     * @return \model\Article|object
     */
    public function insert(ArticleDTO $article):IArticle
    {
        $sth = $this->db->prepare(
            "INSERT INTO tbl_article (title, content, userId) " .
            "VALUES (:title, :content, :userId)"
        );

        $sth->bindValue(':title', $article->title, \PDO::PARAM_STR);
        $sth->bindValue(':content', $article->content, \PDO::PARAM_STR);
        $sth->bindValue(':userId', $article->getUser()->id, \PDO::PARAM_INT);
        $sth->execute();


        $id = $this->db->lastInsertId();
        $article = $this->find($id);

        $userMapper = new UserMapper($this->db);
        try {
            $user = $userMapper->find($article->getUser()->getId());
            $article->setUser($user);
            $user->addArticle($article);
        } catch (\OutOfBoundsException $e) {
            // no articles at the database.
            throw $e;
        }

        return $article;
    }

    /**
     * @param IArticle $article
     * @throws MapperException
     * @return boolean
     */
    public function update(IArticle $article)
    {
        if (false === $this->identityMap->contains($article)) {
            throw new MapperException('Object has no ID, cannot update.');
        }

        $sth = $this->db->prepare(
            "UPDATE tbl_article " .
            "SET title = :title, content = :content WHERE id = :id"
        );

        $sth->bindValue(':title', $article->getTitle(), \PDO::PARAM_STR);
        $sth->bindValue(':content', $article->getContent(), \PDO::PARAM_STR);
        $sth->bindValue(':id', $this->identityMap->getId($article), \PDO::PARAM_INT);
        $sth->execute();

        return $sth->execute();
    }

    /**
     * @param IArticle $article
     * @throws MapperException
     * @return boolean
     */
    public function delete(IArticle $article)
    {
        if (false === $this->identityMap->contains($article)) {
            throw new MapperException('Object has no ID, cannot delete.');
        }

        $sth = $this->db->prepare(
            "DELETE FROM tbl_article WHERE id = :id LIMIT 1"
        );

        $sth->bindValue(':id', $this->identityMap->getId($article), \PDO::PARAM_INT);
        $sth->execute();

        $user = $article->getUser();
        $user->detachArticle($article);

        $this->identityMap->detach($article);

        return  true;
    }
}
