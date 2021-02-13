<?php
namespace App\Mapper;

use App\Model\User;

class UserMapper extends AbstractDataMapper
{
    protected $entityTable = "users";

    protected function loadEntity(array $row) {
        return new User(array(
            "id"    => (int)$row["id"],
            "name"  => $row["name"],
            "email" => $row["email"],
            "role"  => $row["role"]));
    }
}