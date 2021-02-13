<?php
namespace App;

interface UserMapperInterface
{
    public function fetchById($id);
    public function fetchAll(array $conditions = array());
}