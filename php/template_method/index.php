<?php


abstract class Drink{

    // Общий алгоритм приготовления, а отличные детали переопределенны
    //Шаблонный метод определяет скелет алгоритма.
    final public function templateMethod(): void
    {
        $this->step_cook_1();
        $this->step_cook_2();
        $this->step_cook_3();
        $this->step_cook_4();

    }
    function step_cook_1(){
        print(__CLASS__." ".__METHOD__."\n");
    }
    function step_cook_3(){
        print(__CLASS__." ".__METHOD__."\n");
    }
    abstract function step_cook_2();
    abstract function step_cook_4();
}

class Coffe extends Drink{
    function __construct(){}
    function step_cook_2(){
        print(__CLASS__." ".__METHOD__."\n");
    }
    function step_cook_4(){
        print(__CLASS__." ".__METHOD__."\n");
    }
}

class Tea extends Drink{
    function __construct(){}
    function step_cook_1(){
        print(__CLASS__." ".__METHOD__."\n");
    }
    function step_cook_2(){
        print(__CLASS__." ".__METHOD__."\n");
    }
    function step_cook_4(){
        print(__CLASS__." ".__METHOD__."\n");
    }
}

function cook(Drink $obj){
    $obj->templateMethod();
    echo "\n";
}


cook(new Tea());
cook(new Coffe());
/*
Tea Tea::step_cook_1
Tea Tea::step_cook_2
Drink Drink::step_cook_3
Tea Tea::step_cook_4

Drink Drink::step_cook_1
Coffe Coffe::step_cook_2
Drink Drink::step_cook_3
Coffe Coffe::step_cook_4

 */