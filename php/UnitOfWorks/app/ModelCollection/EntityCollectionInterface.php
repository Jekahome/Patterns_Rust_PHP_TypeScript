<?php
namespace App\ModelCollection;

use App\Model\EntityInterface;

interface EntityCollectionInterface extends \Countable, \ArrayAccess, \IteratorAggregate
{
    public function add(EntityInterface $entity);
    public function remove(EntityInterface $entity);
    public function get($key);
    public function exists($key);
    public function clear();
    public function toArray();
}