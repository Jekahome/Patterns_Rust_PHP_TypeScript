<?php
namespace App\LibraryStorage;

class ObjectStorage extends \SplObjectStorage implements ObjectStorageInterface
{
    public function clear() {
        $tempStorage = clone $this;
        $this->addAll($tempStorage);// Добавляет все объекты из другого хранилища
        $this->removeAll($tempStorage);// Удаляет объекты, содержащиеся в другом хранилище, из текущего хранилища
        $tempStorage = null;
    }
}