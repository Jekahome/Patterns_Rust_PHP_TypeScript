<?php
namespace model;

class User implements IUser
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $nickname;

    /**
     * @var null|string
     */
    protected $password;

    /**
     * One User has many Article instances.
     * @var array A list of IArticle instances.
     */
    protected $articles;

    /**
     * @param string $nickname
     * @param string $password
     */
    public function __construct($nickname, $password)
    {
        $this->nickname = $nickname;
        $this->password = $password;
        $this->articles = [];
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return User
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $title
     * @param string $content
     * @return User
     */
    public function addArticle(IArticle $article)
    {
        $article->setUser($this);
        if(!in_array($article,$this->articles)){
            array_push($this->articles,$article);
        }

        return $this;
    }

    public function detachArticle(IArticle $article){
        if(in_array($article,$this->articles)){
            $key = array_search($article, $this->articles);
            unset($this->articles[$key]);
        }
    }
    /**
     * @param array $article List of Article objects.
     * @return User
     */
    public function setArticles(array $article)
    {
        $this->articles = $article;
        return $this;
    }

    /**
     * @return array A list of Article instances.
     */
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * @return boolean
     */
    public function hasArticles()
    {
        return (true === is_array($this->articles) && false === empty($this->articles));
    }
}
