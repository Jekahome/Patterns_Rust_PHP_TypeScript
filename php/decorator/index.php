<?php
declare(strict_types=1);



class Size {
    public const TALL = "TALL";
    public const GRANDE = "GRANDE";
    public const VENTI = "VENTI";

    private string $size;
    function __construct(string $name = "TALL")
    {
        switch ($name) {
            case "TALL":
            case "GRANDE":
            case "VENTI":
                $this->size=$name;
                break;
            default:
                return false;
        }
    }
    public function getConst():string {
        return $this->size;
    }
    public function compare(string $name):bool{
        if(empty($this->size) || empty($name))return false;
        return  $this->size === $name;
    }
}

abstract class Beverage {
    public Size $size;
    public string $description;
    abstract public function cost():float;
    abstract public function getDescription():string;
    function setSize(Size $size){
        $this->size = $size;
    }
    function getSize():Size{
        return $this->size;
    }
}




// Виды напитка
class  HouseBlend extends Beverage{
    private float $defaultCost = 0.89;
    function __construct(Size $size,string $description="House Blend Coffee")
    {
        $this->description = $description;
        $this->setSize($size);
    }
    function getDescription():string{
        return $this->description;
    }
    public function cost():float{
        $cost =  $this->defaultCost;
        switch ($this->getSize()->getConst()) {
            case Size::TALL:
                $cost =  $this->defaultCost;
                break;
            case Size::GRANDE:
                $cost += 0.35;
                break;
            case Size::VENTI:
                $cost += 0.40;
                break;
        }
        return $cost;
    }
}
class  DarkRoast extends Beverage{
    private float $defaultCost = 1.0;
    function __construct(Size $size,string $description="DarkRoast")
    {
        $this->description = $description;
        $this->setSize($size);
    }
    function getDescription():string{
        return $this->description;
    }
    public function cost():float{
        $cost =  $this->defaultCost;
        switch ($this->getSize()->getConst()) {
            case Size::TALL:
                $cost =  $this->defaultCost;
                break;
            case Size::GRANDE:
                $cost += 0.35;
                break;
            case Size::VENTI:
                $cost += 0.40;
                break;
        }
        return $cost;
    }
}

class  Espresso extends Beverage{
    private float $defaultCost = 1.99;
    function __construct(Size $size,string $description="Espresso")
    {
        $this->description = $description;
        $this->setSize($size);
    }
    function getDescription():string{
        return $this->description;
    }
    public function cost():float{
        $cost =  $this->defaultCost;
        switch ($this->getSize()->getConst()) {
            case Size::TALL:
                $cost =  $this->defaultCost;
                break;
            case Size::GRANDE:
                $cost += 0.40;
                break;
            case Size::VENTI:
                $cost += 0.45;
                break;
        }
        return $cost;
    }
}
class  Decaf extends Beverage{
    private float $defaultCost = 1.99;
    function __construct(Size $size,string $description="Decaf")
    {
        $this->description = $description;
        $this->setSize($size);
    }
    function getDescription():string{
        return $this->description;
    }
    public function cost():float{
        $cost =  $this->defaultCost;
        switch ($this->getSize()->getConst()) {
            case Size::TALL:
                $cost =  $this->defaultCost;
                break;
            case Size::GRANDE:
                $cost += 0.22;
                break;
            case Size::VENTI:
                $cost += 0.32;
                break;
        }
        return $cost;
    }
}


// Дополнения к напитку
// Взаимозаменяемые обьекты по признаку Beverage
// Работаем как с обвертками
abstract class CondimentDecorator extends Beverage{
    public Beverage $beverage;
    function getSize():Size{
        return $this->beverage->getSize();
    }
}


//Шоколад Моккачино
class Mocha extends CondimentDecorator{
    function __construct(Beverage $beverage) {
        $this->beverage = $beverage;
    }
    function getDescription():string{// для основных напитков описание уже реализованно
        return $this->beverage->getDescription() . ", Mocha";
    }
    public function cost(): float {
       $cost =  $this->beverage->cost();
        switch ($this->beverage->getSize()->getConst()) {
            case Size::TALL:
                $cost += 0.20;
                break;
            case Size::GRANDE:
                $cost += 0.35;
                break;
            case Size::VENTI:
                $cost += 0.40;
                break;
        }
        return $cost;
    }
}

class Milk extends CondimentDecorator{
    function __construct(Beverage $beverage) {
        $this->beverage = $beverage;
    }
    function getDescription():string{// для основных напитков описание уже реализованно
        return $this->beverage->getDescription() . ", Milk";
    }
    public function cost(): float {
        $cost =  $this->beverage->cost();
        switch ($this->beverage->getSize()->getConst()) {
            case Size::TALL:
                $cost += 0.15;
                break;
            case Size::GRANDE:
                $cost += 0.25;
                break;
            case Size::VENTI:
                $cost += 0.30;
                break;
        }
        return $cost;
    }
}
// Соя
class Soy extends CondimentDecorator{
    function __construct(Beverage $beverage) {
        $this->beverage = $beverage;
    }
    function getDescription():string{// для основных напитков описание уже реализованно
        return $this->beverage->getDescription() . ", Soy";
    }
    public function cost(): float {
        $cost =  $this->beverage->cost();
        switch ($this->beverage->getSize()->getConst()) {
            case Size::TALL:
                $cost += 0.10;
                break;
            case Size::GRANDE:
                $cost += 0.15;
                break;
            case Size::VENTI:
                $cost += 0.20;
                break;
        }
        return $cost;
    }
}
//Взбитые сливки
class Whip extends CondimentDecorator{
    function __construct(Beverage $beverage) {
        $this->beverage = $beverage;
    }
    function getDescription():string{// для основных напитков описание уже реализованно
        return $this->beverage->getDescription() . ", Whip";
    }
    public function cost(): float {
        $cost =  $this->beverage->cost();
        switch ($this->beverage->getSize()->getConst()) {
            case Size::TALL:

                $cost += 0.10;
                break;
            case Size::GRANDE:
                $cost += 0.15;
                break;
            case Size::VENTI:
                $cost += 0.20;
                break;
        }
        return $cost;
    }
}


// Кофе Espresso
$espresso = new Espresso(new Size("VENTI"));
echo ($espresso->getDescription()." $".$espresso->cost()."\n");

//Кофе с двойным шоколадом и взбитыми сливками
$dark_roast = new DarkRoast(new Size());
$dark_roast = new Mocha($dark_roast);
$dark_roast = new Mocha($dark_roast);
$dark_roast = new Milk($dark_roast);
echo ($dark_roast->getDescription()." $".$dark_roast->cost()."\n");

// Кофе "Домашняя смесь" с соей,шоколадом и взбитыми сливками
$house_blend = new HouseBlend(new Size("VENTI"));
$house_blend = new Soy($house_blend);
$house_blend = new Mocha($house_blend);
$house_blend = new Whip($house_blend);
echo ($house_blend->getDescription()." $".$house_blend->cost()."\n");

// Espresso $2.44
// DarkRoast, Mocha, Mocha, Milk $1.55
// House Blend Coffee, Soy, Mocha, Whip $2.09