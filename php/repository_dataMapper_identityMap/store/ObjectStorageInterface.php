<?php
namespace store;

interface ObjectStorageInterface extends \Countable, \Iterator, \ArrayAccess
{
    public function attach(object $object, mixed $data = null);
    public function detach(object $object);
    public function clear();
}