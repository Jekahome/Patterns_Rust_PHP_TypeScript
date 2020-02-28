<?php

class Config{
    static private $_instance = null;
    public $login;
    public $password;
    private function __construct(){$this->login = "0000"; $this->password = "------";} //возможность вызова только из getInstance
    private function __clone(){} // запрещаем клонирование
    static function getInstance(){
        if(self::$_instance == null){
            self::$_instance = new Config();
        }
        return self::$_instance;
    }
    public function setLogin($login){
        $this->login = $login;
    }
    public function setPassword($password){
        $this->password = $password;
    }
    public function __sleep() {
        return  array('login',  'password');
    }

    public function __wakeup() {
        if(self::$_instance){
            $this->login =  self::$_instance->login;
            $this->password = self::$_instance->password;
        }
    }
}

function foo1(string $login,string $password){
    $config = Config::getInstance();
    $config->setLogin($login);
    $config->setPassword($password);
}
function foo2(string $login,string $password){
    $config = Config::getInstance();
    $config->setLogin($login);
    $config->setPassword($password);
}


foo1("key1","1234");
$ser_config = serialize(Config::getInstance());

$config = Config::getInstance();
echo $config->login," / ",$config->password,"\n";

foo1("key2","5656");


$unser_config= unserialize($ser_config);// восстановился из текущего состояния
echo $unser_config->login,"==key2 / ",$unser_config->password,"==5656\n";