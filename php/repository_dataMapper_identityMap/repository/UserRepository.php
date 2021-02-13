<?php
namespace repository;
use persistence\UserMapperInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $userMapper;

    public function __construct(UserMapperInterface $userMapper) {
        $this->userMapper = $userMapper;
    }

    public function fetchById($id) {
        return $this->userMapper->find($id);
    }
   // различные  методы поиска....
}