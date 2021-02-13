<?php
namespace model;

interface IArticle{
    public function getTitle();
    public function setTitle($title);
    public function getContent();
    public function setContent($content);
    public function getUser();
    public function setUser(IUser $user);

}