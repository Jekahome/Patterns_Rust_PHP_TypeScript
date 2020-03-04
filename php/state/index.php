<?php
declare(strict_types=1);

// Состояние — это поведенческий паттерн, позволяет объектам менять поведение в зависимости от своего состояния.
// Извне создаётся впечатление, что изменился класс объекта.
//Применяется с машиной состояний(стейт-машина или конечный автомат)
//Основная идея в том, что программа может находиться в одном из нескольких состояний, которые всё время сменяют друг друга.
//Вместо того, чтобы хранить код всех состояний, первоначальный объект, называемый контекстом,
// будет содержать ссылку на один из объектов-состояний и делегировать ему работу, зависящую от состояния.


abstract class State{
    protected GumballMashine $gumballMashine;
    abstract function insertQuarter();//бросить монетку в автомат
    abstract function ejectQuarter();//вернуть монетку из автомата
    abstract function turnCrank();//дернуть за рычаг
    abstract function  dispense();//выдать шарик
}

//Классы состояний

// Состояние продано
class SoldState extends State{
    public function __construct(GumballMashine $gumballMashine)
    {
        $this->gumballMashine = $gumballMashine;
    }

     function insertQuarter(){
         echo("Пожалуйста, подождите, мы уже даем вам шарик\n");
     }
     function ejectQuarter(){
         echo("Извините, вы уже повернули ручку\n");
     }
     function turnCrank(){
         echo("Поворот дважды не даст вам еще один шарик!\n");
     }
     function  dispense(){
        $this->gumballMashine->realeseBall();
        if(  $this->gumballMashine->getCount() > 0){
            $this->gumballMashine->setState($this->gumballMashine->getNoQuarterState());
        }else{
            echo("Шариков больше нет\n");
            $this->gumballMashine->setState($this->gumballMashine->getSoldOutState());
        }
     }
     function __toString()
     {
         return "SoldState Состояние продано\n";
     }
}
// Состояние все продано, нет шариков
class SoldOutState extends State{
    public function __construct(GumballMashine $gumballMashine)
    {
        $this->gumballMashine = $gumballMashine;
    }

    function insertQuarter(){
        echo("Вы не можете вставить монетку, шарики распроданы\n");
    }
    function ejectQuarter(){
        echo("Вы не можете извлечь, вы еще не вставили монетку\n");
    }
    function turnCrank(){
        echo("Шариков нет!\n");
    }
    function  dispense(){
        echo("Шариков нет\n");
    }
    function __toString()
    {
        return "SoldOutState Состояние все продано, нет шариков\n";
    }
}
//Состояние монетка не внесена
class NoQuarterState extends State{
    public function __construct(GumballMashine $gumballMashine)
    {
        $this->gumballMashine = $gumballMashine;
    }

    function insertQuarter(){
        echo("Монетка внесена\n");
        $this->gumballMashine->setState($this->gumballMashine->getHasQuarterState());
    }
    function ejectQuarter(){
        echo("Монетка не была внесена!\n");
    }
    function turnCrank(){
        echo("Нет монетки, нет шарика, вот такие дела\n");
    }
    function  dispense(){
       echo("Сначала внесите монетку\n");
    }
    function __toString()
    {
        return "NoQuarterState Состояние монетка не внесена\n";
    }
}
//Состояние монетка внесена
class HasQuarterState extends State{
    public function __construct(GumballMashine $gumballMashine)
    {
        $this->gumballMashine = $gumballMashine;
    }

    function insertQuarter(){
        echo("Монетки больше не принимаются\n");
    }
    function ejectQuarter(){
        echo("Возврат монетки\n");
        $this->gumballMashine->setState($this->gumballMashine->getNoQuarterState());
    }
    function turnCrank(){
        echo("Получение шарика\n");
        $this->gumballMashine->setState($this->gumballMashine->getSoldState());
    }
    function  dispense(){
        echo("Шариков нет\n");
    }
    function __toString()
    {
        return "HasQuarterState Состояние монетка внесена\n";
    }
}

class GumballMashine{
    private State $soldState;
    private State $soldOutState;
    private State $noQuarterState;
    private State $hasQuarterState;

    private State $state;
    private int $count;

    public function __construct(int $numberGumballs)
    {
        $this->count = $numberGumballs;
        $this->soldState=new SoldState($this);
        $this->soldOutState=new SoldOutState($this);
        $this->noQuarterState=new NoQuarterState($this);
        $this->hasQuarterState=new HasQuarterState($this);

        if($this->count > 0){
            $this->state = $this->noQuarterState;// Начальное состояние монетка не внесена
        }else{
            $this->state = $this->soldOutState;// Иначе шариков нет
        }
    }
    function insertQuarter(){
        $this->state->insertQuarter();
    }
    function ejectQuarter(){
       $this->state->ejectQuarter();
    }
    function turnCrank(){
        $this->state->turnCrank();
        $this->state->dispense();
    }
    // Перевод автомата в другое состояние
    function  setState(State $state){
        $this->state=$state;
    }
    //Выдача шарика
    function realeseBall(){
        echo("Gumball выдает шарик!\n");
        if($this->count != 0){
            $this->count = $this->count - 1;
        }
    }

    function  getSoldState():State{
        return $this->soldState;
    }
    function  getSoldOutState():State{
        return $this->soldOutState;
    }
    function getNoQuarterState():State{
        return $this->noQuarterState;
    }
    function getHasQuarterState():State{
        return $this->hasQuarterState;
    }
    function  getCount():int{
        return $this->count;
    }
    function __toString()
    {
        return $this->state->__toString();
    }
}


$gumballMashine  = new GumballMashine(3);

$gumballMashine->insertQuarter();//бросить монетку
$gumballMashine->turnCrank();//опустить рычаг
echo $gumballMashine."\n";//текущий статус

$gumballMashine->insertQuarter();//бросить монетку
$gumballMashine->ejectQuarter();//вернуть монетку
$gumballMashine->turnCrank();//опустить рычаг
echo $gumballMashine."\n";//текущий статус

$gumballMashine->insertQuarter();//бросить монетку
$gumballMashine->insertQuarter();//бросить монетку
$gumballMashine->turnCrank();//опустить рычаг
echo $gumballMashine."\n";//текущий статус

$gumballMashine->insertQuarter();//бросить монетку
echo $gumballMashine."\n";//текущий статус


/*
Монетка внесена
Получение шарика
Gumball выдает шарик!
NoQuarterState Состояние монетка не внесена

Монетка внесена
Возврат монетки
Нет монетки, нет шарика, вот такие дела
Сначала внесите монетку
NoQuarterState Состояние монетка не внесена

Монетка внесена
Монетки больше не принимаются
Получение шарика
Gumball выдает шарик!
NoQuarterState Состояние монетка не внесена

Монетка внесена
HasQuarterState Состояние монетка внесена

 */


















