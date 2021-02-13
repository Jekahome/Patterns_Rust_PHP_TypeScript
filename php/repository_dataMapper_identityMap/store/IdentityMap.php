<?php
namespace store;

class IdentityMap
{
    private ObjectStorage $storage;
    private $idToObject;
    private static $_instance;

    private function __construct()
    {
        $this->storage = new ObjectStorage();
        $this->idToObject = new \ArrayObject();
    }

    static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new IdentityMap();
        }
        return self::$_instance;
    }

    public function attach($id, $object):void
    {
        $key = $this->buildKey($id,$object);
        if($this->contains($object) ){
            throw new \OverflowException();
        }
        if (isset($this->idToObject[$key] )){
            throw new \OverflowException();
        }

        $this->idToObject[$key]     = $object;
        $this->storage->attach($object, $key);
    }

    public function detach($object):void
    {
        $id = $this->storage[$object];
        $this->storage->detach($object);
        unset( $this->idToObject[$id] );
    }

    public function getObject($id, string $class):object
    {
        $key =  $this->buildKey($id,$class);
        if(!isset($this->idToObject[$key]))
            throw new \OutOfBoundsException();
        return $this->idToObject[$key];
    }

    public function hasId($id,string $class):bool
    {
        $key =  $this->buildKey($id,$class);
        return isset($this->idToObject[$key]);
    }

    public function getId($object)
    {
        if (false === $this->contains($object)) {
            throw new \OutOfBoundsException();
        }
        $id = $this->storage[$object];
        return $this->slpitKey($id);
    }

    public function contains($object):bool
    {
        return $this->storage->contains($object);
    }

    private function buildKey($id,$object):string{
        if(is_object($object))
            return $id."_".get_class($object);
        else if(is_string($object))
            return $id."_".$object;
        else throw new \InvalidArgumentException();
    }

    private function slpitKey(string $id):string {
        $arr = explode("_",$id);
        return array_shift($arr);
    }
}
