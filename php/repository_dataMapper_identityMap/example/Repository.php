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
use repository\UserRepository;

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



try{

        printf("\033[%s%s\033[0m\n","1;36m","Использование Repository поверх DataMapper'a \n");
    $userMapper = new UserMapper($db);
    $userRepository = new UserRepository($userMapper);
    /**
     * @var $entityUser \model\IUser
     */
    $entityUser_1 = $userRepository->fetchById(1);
        printf("User: id:%d, name:%s, password:%s\n",$entityUser_1->getId(),$entityUser_1->getNickname(),$entityUser_1->getPassword());
    $articles = $entityUser_1->getArticles();
    foreach ($articles as $entityArticle){
        printf("Article: title:%s, content:%s\n",$entityArticle->getTitle(),$entityArticle->getContent());
    }



    clear($db);
}catch (\Throwable $e){
    clear($db);
    throw $e;
}


































