<?php
namespace model;

class Article implements IArticle
{
    /**
     * @var null|string
     */
    protected $title;

    /**
     * @var null|string
     */
    protected $content;

    /**
     * User has many Articles.
     * @var IUser
     */
    protected $user;

    /**
     * @param string $title
     * @param string $content
     */
    public function __construct($title, $content)
    {
        $this->title   = $title;
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Article
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Article
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * @return IUser
     */
    public function getUser()
    {
        return $this->user;
    }

    public function detachUser(){
        $this->user = [];
    }

    /**
     * @param IUser $user
     * @return Article
     */
    public function setUser(IUser $user)
    {
        $this->user = $user;
        return $this;
    }
}
