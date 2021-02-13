<?php
namespace example;

// php.ini zend.assertions=1
ini_set("zend.assertions", "1");
ini_set("assert.exception", "1");
assert_options(\ASSERT_ACTIVE, 1);
assert_options(\ASSERT_WARNING, 0);

require "../autoload.php";


use model\Article;
use persistence\ArticleMapper;
use persistence\UserMapper;
use model\UserDTO;
use model\ArticleDTO;

$db  = new \PDO("sqlite:datamapper.db");
function load(\PDO $db){
    $userSQL = <<<USER
CREATE TABLE IF NOT EXISTS tbl_user (
  id       INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  nickname VARCHAR(30) NOT NULL,
  password VARCHAR(30) NOT NULL
);
USER;
    $articleSQL = <<<ARTICLE
CREATE TABLE IF NOT EXISTS tbl_article (
  id      INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  title   VARCHAR(30) NOT NULL,
  content VARCHAR(255) NOT NULL,
  userId  INT NOT NULL,
  FOREIGN KEY (userId)
  REFERENCES tbl_user (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);
ARTICLE;
    $db->exec($userSQL);
    $db->exec($articleSQL);

    $stm = $db->prepare("INSERT INTO tbl_user(id,nickname, password) VALUES (?, ?, ?)");
    $stm->execute(array(1,"Tom","12345"));
    $stm->execute(array(2,"Jerry","12345"));

    $stm = $db->prepare("INSERT INTO tbl_article (id,title,content,userId) VALUES (?,?,?,?)");
    $stm->execute(array(1,"Title 1","Content 1",1));
    $stm->execute(array(2,"Title 2","Content 2",1));
    $stm->execute(array(3,"Title 3","Content 3",2));
    $stm->execute(array(4,"Title 4","Content 4",2));

    // $ sqlite3
    // sqlite>  .open datamapper.db
    // sqlite>  select * from tbl_user;
}

function clear(\PDO $db){
    $userSQL = <<<USER
DROP TABLE IF EXISTS  tbl_article    
USER;
    $articleSQL = <<<ARTICLE
DROP TABLE IF EXISTS tbl_user
ARTICLE;

    $db->exec($articleSQL);
    $db->exec($userSQL);
}

load($db);

// Просто отображение (map) структуры обьекта в базе на структуру в обьекта в PHP
// Операется на работу другого паттерна - IdentityMap

try{
    // 1.Создание mappers'ов
    $mapUser = new UserMapper($db);
    /**
     * @var $user \model\User
     */
    $entityUser_1 = $mapUser->find(1);
    assert($entityUser_1->getId() == 1 );
    assert($entityUser_1->getNickname() == "Tom" );


    $mapArticle = new ArticleMapper($db);
    /**
     * @var $article \model\Article
     */
    $entityArticle_1 = $mapArticle->find(1);
    assert($entityArticle_1->getTitle() == "Title 1" );
    assert($entityArticle_1->getUser() === $entityUser_1 );
    assert(spl_object_hash($entityArticle_1->getUser()) == spl_object_hash($entityUser_1));
    assert(spl_object_id($entityArticle_1->getUser()) == spl_object_id($entityUser_1));



//2. Добавление новых бизнес обьектов (через DTO обьекты)
    $userDTO = new UserDTO("Mouse","12345");
    $entityUser_last = $mapUser->insert($userDTO);

    $articleDTO = new ArticleDTO("Title 5","Content 5");
    $userDTO->id = $entityUser_last->getId();
    $articleDTO->setUser($userDTO);
    $entityArticle_last = $mapArticle->insert($articleDTO);
    assert($entityArticle_last->getUser() === $entityUser_last );
    assert(spl_object_hash($entityArticle_last->getUser()) == spl_object_hash($entityUser_last));
    assert(spl_object_id($entityArticle_last->getUser()) == spl_object_id($entityUser_last));

//3. Добавить бизнес модели пользователя бизнес модель артикля
// Проверка ссылок друг на друга

    $entityUser_1 = $mapUser->find(1);
    $entityUser_1->addArticle($entityArticle_last);
    // пользователь и пользователь артикля совпадают
    assert($entityArticle_last->getUser() === $entityUser_1 );
    assert(spl_object_hash($entityArticle_last->getUser()) == spl_object_hash($entityUser_1));
    assert(spl_object_id($entityArticle_last->getUser()) == spl_object_id($entityUser_1));

    // все артикли пользователя совпадают с пользователем
    $entityArticles = $entityUser_1->getArticles();
    /**
     * @var $itemArticle \model\Article
     */
    foreach ($entityArticles as $itemArticle){
        assert(spl_object_hash($itemArticle->getUser()) == spl_object_hash($entityUser_1));
        assert(spl_object_id($itemArticle->getUser()) == spl_object_id($entityUser_1));
    }



// Обновление единственных обьектов в системе
    $entityUser = $mapUser->find(1);
    $entityUser->setPassword("dddd");
    $mapUser->update($entityUser);

    $article = $mapArticle->find(1);
    $article->setContent("ffff");
    $mapArticle->update($article);


// Просмотр изменений

    // Пользователи
    printf("\033[%s%s\033[0m\n","1;36m","Просмотр пользователей");
    for($idU=1;$idU<4;$idU++){
        $entityUser = $mapUser->find($idU);
        printf("\nUser: id:%d, name:%s, password:%s\n",$entityUser->getId(),$entityUser->getNickname(),$entityUser->getPassword());
        $articles = $entityUser->getArticles();
       foreach ($articles as $article){
           printf("Article: title:%s, content:%s\n",$article->getTitle(),$article->getContent());
       }
    }
    // Артикли
    printf("\033[%s%s\033[0m\n","1;36m","Просмотр артиклей");
    for($idA=1;$idA<6;$idA++){
        $article = $mapArticle->find($idA);
        printf("\nArticle: title:%s, content:%s\n",$article->getTitle(),$article->getContent());
        $user = $article->getUser();
        printf("User: id:%d, name:%s, password:%s\n",$user->getId(),$user->getNickname(),$user->getPassword());
    }



// Удаление ссылок

    printf("\033[%s%s\033[0m\n","1;36m","У пользователя стало меньше артиклей\n");
    $entityArticle_2 = $mapArticle->find(2);
    $mapArticle->delete($entityArticle_2);
    $entityUser_1 = $mapUser->find(1);
    $articles = $entityUser_1->getArticles();
    foreach ($articles as $article){
        printf("Article: title:%s, content:%s\n",$article->getTitle(),$article->getContent());
    }

    $entityArticle_1 = $mapArticle->find(1);
    $entityUser_1 = $mapUser->find(1);
    $mapUser->delete($entityUser_1);

    assert($entityArticle_1->getUser() == []);

    printf("\033[%s%s\033[0m\n","1;36m","Все пользователи\n");
    $entityUsers = $mapUser->fetchAll();
    foreach($entityUsers as $entityUser){
        printf("User: id:%d, name:%s, password:%s\n",$entityUser->getId(),$entityUser->getNickname(),$entityUser->getPassword());
    }


    clear($db);
}catch (\Throwable $e){
    clear($db);
    throw $e;
}



