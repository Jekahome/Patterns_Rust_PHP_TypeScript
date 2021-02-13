<?php
namespace persistence;

interface UserMapperInterface
{
    public function find($id);
    public function fetchAll():array;
}