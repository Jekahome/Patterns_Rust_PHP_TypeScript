<?php
namespace model;

class ArticleDTO
{
    public string $title;
    public string $content;

    /**
     * User has many Articles.
     * @var $user UserDTO
     */
    protected $user;

    public function __construct(string $title, string $content)
    {
        $this->title   = $title;
        $this->content = $content;
    }

    /**
     * @return UserDTO
     */
    public function getUser():UserDTO
    {
        return $this->user;
    }

    /**
     * @param IUser $user
     * @return Article
     */
    public function setUser(UserDTO $user):ArticleDTO
    {
        $this->user = $user;
        return $this;
    }
}
