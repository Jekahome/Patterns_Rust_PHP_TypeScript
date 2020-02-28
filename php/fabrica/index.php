<?php
declare(strict_types=1);

// Суть в инкапсуляции создания конкретных типов обьектов
//Абстрактная фабрика как следсвие уменьшения зависимости от фабрики шаблона Фабрика

// enum
class PizzaType {
    public const CHEESE = "CHEESE";
    public const PEPPERONI = "PEPPERONI";

    private string $pizza;
    function __construct(string $pizza = PizzaType::CHEESE)
    {
        switch ($pizza) {
            case "CHEESE":
            case "PEPPERONI":
                $this->pizza=$pizza;
                break;
            default:
                return false;
        }
    }
    public function getConst():string {
        return $this->pizza;
    }
}


// Классы создатели
abstract class PizzaStore {
    function orderPizza(PizzaType $type):Pizza{
        $pizza = $this->createPizza($type);
        $pizza->prepare();
        $pizza->bake();
        $pizza->cut();
        $pizza->box();
        return $pizza;
    }
    abstract public function createPizza(PizzaType $type):Pizza;
}

class ChicagoPizzaStore extends PizzaStore{
    public function createPizza(PizzaType $type):Pizza{
        if($type->getConst() == PizzaType::CHEESE){
            return new ChicagoStyleSheesePizza();
        }else{
            return new ChicagoStylePepperoniPizza();
        }
    }
}
class NYPizzaStore extends PizzaStore{
    public function createPizza(PizzaType $type):Pizza{
        if($type->getConst() == PizzaType::CHEESE){
            return new NYStyleSheesePizza();
        }else{
            return new NYStylePepperoniPizza();
        }
    }
}



// Классы продукты
abstract class Pizza {
    public string $name;
    function prepare(){
        echo("prepare {$this->name}\n");
    }
    function bake(){
        echo("bake {$this->name}\n");
    }
    function cut(){
        echo("cut {$this->name}\n");
    }
    function box(){
        echo("box {$this->name}\n");
    }
}


class ChicagoStyleSheesePizza extends Pizza{
    function __construct()
    {
        $this->name =  __CLASS__;
    }
}
class ChicagoStylePepperoniPizza extends Pizza{
    function __construct()
    {
        $this->name =  __CLASS__;
    }
}
class NYStyleSheesePizza extends Pizza{
    function __construct()
    {
        $this->name =  __CLASS__;
    }
}
class NYStylePepperoniPizza extends Pizza{
    function __construct()
    {
        $this->name =  __CLASS__;
    }
}


$ny_factory = new NYPizzaStore();
$pizza = $ny_factory->orderPizza(new PizzaType("CHEESE"));
$pizza = $ny_factory->orderPizza(new PizzaType("PEPPERONI") );


$chicago_factory  = new ChicagoPizzaStore();
$pizza = $chicago_factory->orderPizza(new PizzaType("CHEESE"));
$pizza = $chicago_factory->orderPizza(new PizzaType("PEPPERONI"));


/*
prepare NYStyleSheesePizza
bake NYStyleSheesePizza
cut NYStyleSheesePizza
box NYStyleSheesePizza

prepare NYStylePepperoniPizza
bake NYStylePepperoniPizza
cut NYStylePepperoniPizza
box NYStylePepperoniPizza

prepare ChicagoStyleSheesePizza
bake ChicagoStyleSheesePizza
cut ChicagoStyleSheesePizza
box ChicagoStyleSheesePizza

prepare ChicagoStylePepperoniPizza
bake ChicagoStylePepperoniPizza
cut ChicagoStylePepperoniPizza
box ChicagoStylePepperoniPizza

 */