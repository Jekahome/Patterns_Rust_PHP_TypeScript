<?php
namespace App;

use App\UserInterface;

interface UserCollectionInterface extends \Countable, \ArrayAccess, \IteratorAggregate
{
    public function add(UserInterface $user);
    public function remove(UserInterface $user);
    public function get($key);
    public function exists($key);
    public function clear();
    public function toArray();
}
