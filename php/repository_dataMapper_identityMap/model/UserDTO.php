<?php
namespace model;

class UserDTO
{
    public int $id;
    public string $nickname;
    public string $password;

    /**
     * One User has many Article instances.
     * @var array A list of ArticleDTO instances.
     */
    public $articles;

    public function __construct(string $nickname, string $password)
    {
        $this->nickname = $nickname;
        $this->password = $password;
    }

    /**
     * @param string $title
     * @param string $content
     * @return User
     */
    public function addArticle(ArticleDTO $article):UserDTO
    {
        $this->articles[] = $article->setUser($this);
        return $this;
    }

    /**
     * @param array $article List of Article objects.
     * @return User
     */
    public function setArticles(array $article):UserDTO
    {
        $this->articles = $article;
        return $this;
    }

    /**
     * @return array A list of ArticleDTO instances.
     */
    public function getArticles():array
    {
        return $this->articles;
    }

    /**
     * @return boolean
     */
    public function hasArticles():bool
    {
        return (true === is_array($this->articles) && false === empty($this->articles));
    }

}