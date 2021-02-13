<?php
namespace repository;

interface UserRepositoryInterface
{
    public function fetchById($id);
    // различные методы поиска....
    /*public function fetchByName($name);
    public function fetchbyEmail($email);
    public function fetchByRole($role);*/
}