<?php

namespace App;

interface DatabaseAdapterInterface{
    public function select($rable,$array);
    public function fetch();
    public function fetchAll();

}