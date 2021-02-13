<?php
namespace model;

interface IUser{
    public function getNickname();
    public function setNickname($nickname);
    public function getPassword();
    public function setPassword($password);
    public function getId();
    public function addArticle(IArticle $article);
    public function setArticles(array $article);
    public function getArticles();
    public function hasArticles();
}
