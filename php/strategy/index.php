<?php
declare(strict_types=1);

// интерфейсы поведения
interface FlyBehaviorImpl
{
  function fly():void;
}
interface QuackBehaviorImpl
{
    function quack():string;
}

// классы поведения fly
class FlyWithWings implements FlyBehaviorImpl
{
    function __construct(){}
    public function fly():void { echo "Реализация полета";}
}
class FlyNoWay  implements FlyBehaviorImpl
{
    function __construct(){}
    public function fly():void { echo "Утка не может летать";}
}
// классы поведения quack
class Quack  implements QuackBehaviorImpl
{
    function __construct(){}
    public function quack():string { echo "Кряканье"; return "Кряканье";}
}
class Squeak  implements QuackBehaviorImpl
{
    function __construct(){}
    public function quack():string{  echo "Резиновые утки пищат"; return  "Резиновые утки пищат";}
}
class MuteQuack  implements QuackBehaviorImpl
{
    function __construct(){}
    public function quack():string { echo "Пустая реализация"; return "Пустая реализация кряканья";}
}

// Базовый класс реализация композицией через интерфейс
abstract class Duck
{
    public FlyBehaviorImpl $flyBehavior;
    public QuackBehaviorImpl $quackBehavior;
    function __construct(FlyBehaviorImpl $flyBehavior ,QuackBehaviorImpl $quackBehavior )
    {
        $this->quackBehavior = $quackBehavior;// класс поведения
        $this->flyBehavior =  $flyBehavior;// класс поведения
    }

    abstract function display():string;

    public function swim():string {return "Все утки плавают";}
// Вызов у обьекта реализующего кряканье метод крякания
    public function performQuack():string {return $this->quackBehavior->quack();}
    public function performFly(){$this->flyBehavior->fly();}
    public function setFlyBehavior(FlyBehaviorImpl $fly):void{$this->flyBehavior = $fly;}
    public function setQuackBehavior(QuackBehaviorImpl $quack):void{$this->quackBehavior = $quack;}
}
//Субклассы
// субклассы наследуются от базового класса Duck
class MallardDuck extends Duck
{
    private string $name;

    function __construct($name="Кряква" ){
         parent::__construct(new FlyWithWings(),new Quack());
         $this->name = $name;
    }
    public function swim():string {return "$this->name плавает";}
    public function display():string {return "$this->name она Кряква - это дикая утка";}
}
//резиновая
class RubberDuck extends Duck
{
    private string $name;

    function __construct(string $name = "Резина"){
        parent::__construct(new FlyWithWings(),new Quack());
        $this->name = $name;

    }
    public function swim(): string {return `$this->name плавает`;}
    public function display(): string {return `$this->name она резиновая утка`;}
}
//приманка
class DecoyDuck extends Duck
{
    private string $name;

    function __construct(string $name = "Резина"){
        parent::__construct(new FlyWithWings(),new Quack());
        $this->name = $name;

    }
    public function swim(): string {return `$this->name плавает`;}
    public function display(): string {return `$this->name она из дерева`;}
}

//Имитация утиного кряка (класс не является наследником Duck но методы работы с поведением можно просто скопировать)
class Manok
{
    private QuackBehaviorImpl $quackBehavior;
    public function __construct(QuackBehaviorImpl $quack){
        $this->quackBehavior = $quack;// класс поведения
    }
    public function performQuack(): string{return $this->quackBehavior->quack();}
    public function setQuackBehavior(QuackBehaviorImpl $quack){$this->quackBehavior = $quack;}
}

// Создаем реальную утку из рода Крякв
$duck = new MallardDuck();
$duck->performFly();// по умолчанию она летает
$duck->setFlyBehavior(new FlyWithWings());//изменяем ее поведение на не летает
$duck->performFly();// теперь она не летает

$duck->performQuack();// по умолчанию она крякает
$duck->setQuackBehavior(new Squeak()); //изменяем ее поведение на писк резиновой утки
$value = $duck->performQuack(); // теперь она пищит

$manok = new Manok(new Quack());
$manok->performQuack();
