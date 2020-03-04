// Состояние — это поведенческий паттерн, позволяет объектам менять поведение в зависимости от своего состояния.
// Извне создаётся впечатление, что изменился класс объекта.
//Применяется с машиной состояний(стейт-машина или конечный автомат)
//Основная идея в том, что программа может находиться в одном из нескольких состояний, которые всё время сменяют друг друга.
//Вместо того, чтобы хранить код всех состояний, первоначальный объект, называемый контекстом,
// будет содержать ссылку на один из объектов-состояний и делегировать ему работу, зависящую от состояния.

abstract class State {
    protected gumballMashine:GumballMashine;
    abstract insertQuarter();//бросить монетку в автомат
    abstract ejectQuarter();//вернуть монетку из автомата
    abstract turnCrank();//дернуть за рычаг
    abstract dispense();//выдать шарик
}

//Классы состояний

// Состояние продано
class SoldState extends State{
    constructor(gumballMashine:GumballMashine){
        super();
        this.gumballMashine=gumballMashine;
    }
    insertQuarter(){
        console.log("Пожалуйста, подождите, мы уже даем вам шарик");
    }
    ejectQuarter(){
        console.log("Извините, вы уже повернули ручку");
    }
    turnCrank(){
        console.log("Поворот дважды не даст вам еще один шарик!");
    }
    dispense(){
        this.gumballMashine.realeseBall();
        if(this.gumballMashine.getCount() > 0){
            this.gumballMashine.setState(this.gumballMashine.getNoQuarterState());
        }else{
            console.log("Шариков больше нет");
            this.gumballMashine.setState(this.gumballMashine.getSoldOutState());
        }
    }
    toString(){
        console.log("SoldState Состояние продано");
    }
}
// Состояние все продано, нет шариков
class SoldOutState extends State{
    constructor(gumballMashine:GumballMashine){
        super();
        this.gumballMashine=gumballMashine;
    }
    insertQuarter(){
        console.log("Вы не можете вставить монетку, шарики распроданы");
    }
    ejectQuarter(){
        console.log("Вы не можете извлечь, вы еще не вставили монетку");
    }
    turnCrank(){
        console.log("Шариков нет!");

    }
    dispense(){
        console.log("Шариков нет");
    }
    toString(){
        console.log("SoldOutState Состояние все продано, нет шариков");
    }
}
//Состояние монетка не внесена
class NoQuarterState extends State{
    constructor(gumballMashine:GumballMashine){
        super();
        this.gumballMashine=gumballMashine;
    }
    insertQuarter(){
        console.log("Монетка внесена");
        this.gumballMashine.setState(this.gumballMashine.getHasQuarterState());
    }
    ejectQuarter(){
        console.log("Монетка не была внесена!");
    }
    turnCrank(){
        console.log("Нет монетки, нет шарика, вот такие дела");
    }
    dispense(){
        console.log("Сначала внесите монетку");
    }
    toString(){
        console.log("NoQuarterState Состояние монетка не внесена");
    }
}
//Состояние монетка внесена
class HasQuarterState extends State{
    constructor(gumballMashine:GumballMashine){
        super();
        this.gumballMashine=gumballMashine;
    }
    insertQuarter(){
        console.log("Монетки больше не принимаются");

    }
    ejectQuarter(){
        console.log("Возврат монетки");
        this.gumballMashine.setState(this.gumballMashine.getNoQuarterState());
    }
    turnCrank(){
        console.log("Получение шарика");
        this.gumballMashine.setState(this.gumballMashine.getSoldState());
    }
    dispense(){
        console.log("Шариков нет");
    }
    toString(){
        console.log("HasQuarterState Состояние монетка внесена");
    }
}

class GumballMashine {
    soldState:State;
    soldOutState:State;
    noQuarterState:State;
    hasQuarterState:State;

    state:State;
    count:number;

    constructor(numberGumballs:number){
        this.count = numberGumballs;
        this.soldState=new SoldState(this);
        this.soldOutState=new SoldOutState(this);
        this.noQuarterState=new NoQuarterState(this);
        this.hasQuarterState=new HasQuarterState(this);

        if (this.count > 0){
            this.state = this.noQuarterState;// Начальное состояние монетка не внесена
        }else{
            this.state = this.soldOutState;// Иначе шариков нет
        }
    }
    insertQuarter(){
        this.state.insertQuarter();
    }
    ejectQuarter(){
        this.state.ejectQuarter();
    }
    turnCrank(){
        this.state.turnCrank();
        this.state.dispense();
    }
    // Перевод автомата в другое состояние
    setState(state:State){
        this.state=state;
    }
    //Выдача шарика
    realeseBall(){
        console.log("Gumball выдает шарик!");
        if(this.count != 0){
            this.count = this.count - 1;
        }
    }

    getSoldState():State{
        return this.soldState;
    }
    getSoldOutState():State{
        return this.soldOutState;
    }
    getNoQuarterState():State{
        return this.noQuarterState;
    }
    getHasQuarterState():State{
        return this.hasQuarterState;
    }

    getCount():number{
        return this.count;
    }
    toString(){
        console.log(this.state.toString());
    }
}

let gumballMashine:GumballMashine = new GumballMashine(3);
gumballMashine.insertQuarter();//бросить монетку
gumballMashine.turnCrank();//опустить рычаг
gumballMashine.toString();//текущий статус

gumballMashine.insertQuarter();//бросить монетку
gumballMashine.ejectQuarter();//вернуть монетку
gumballMashine.turnCrank();//опустить рычаг
gumballMashine.toString();//текущий статус

gumballMashine.insertQuarter();//бросить монетку
gumballMashine.insertQuarter();//бросить монетку
gumballMashine.turnCrank();//опустить рычаг
gumballMashine.toString();//текущий статус

gumballMashine.insertQuarter();//бросить монетку
gumballMashine.toString();//текущий статус

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