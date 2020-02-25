
// Декоратор часто используется в связке с Фабрикой или Строителем

// Для возможности создавать разнообразные комбинации добавок при этом эдинообразно использовать обьект


enum Size{TALL,GRANDE, VENTI};

// Напиток
abstract class Beverage {
     public size:Size = Size.TALL;
     description:string;
     getDescription():string{// для основных напитков описание уже реализованно
         return this.description;
     }
     setSize(size:Size){
         this.size = size;
     }
     getSize():Size{
         return this.size;
     }
    abstract cost():number;
}



// Виды напитка
class HouseBlend extends Beverage{
    private defaultCost:number=0.89;
     constructor(size:Size = Size.TALL,description: string = "House Blend Coffee") {
        super();
        this.description = description;
        this.setSize(size);
     }

    public cost():number{
        let cost:number =  this.defaultCost;
        switch (this.getSize()) {
            case Size.TALL:
                cost += 0.20;
                break;
            case Size.GRANDE:
                cost += 0.35;
                break;
            case Size.VENTI:
                cost += 0.40;
                break;
        }
        return cost;
     }
}

class DarkRoast extends Beverage{
    private defaultCost:number=1.0;
    constructor(size:Size = Size.TALL,description: string = "DarkRoast") {
        super();
        this.description = description;
        this.setSize(size);
    }

    public cost():number{
        let cost:number =  this.defaultCost;
        switch (this.getSize()) {
            case Size.TALL:
                cost = this.defaultCost;
                break;
            case Size.GRANDE:
                cost += 0.35;
                break;
            case Size.VENTI:
                cost += 0.40;
                break;
        }
        return cost;
    }
}

class Espresso extends Beverage{
    private defaultCost:number=1.99;
    constructor(size:Size = Size.TALL,description: string = "Espresso") {
        super();
        this.description = description;
        this.setSize(size);
    }

    public cost():number{
        let cost:number =  this.defaultCost;
        switch (this.getSize()) {
            case Size.TALL:
                cost = this.defaultCost;
                break;
            case Size.GRANDE:
                cost += 0.40;
                break;
            case Size.VENTI:
                cost += 0.45;
                break;
        }
        return cost;
    }
}

class Decaf extends Beverage {
    private defaultCost:number=1.0;
    constructor(size:Size = Size.TALL,description: string = "Decaf") {
        super();
        this.description = description;
        this.setSize(size);
    }

    public cost(): number {
        let cost:number = this.defaultCost;
        switch (this.getSize()) {
            case Size.TALL:
                cost = this.defaultCost;
                break;
            case Size.GRANDE:
                cost += 0.22;
                break;
            case Size.VENTI:
                cost += 0.32;
                break;
        }
        return cost;
    }
}

// Дополнения к напитку
// Взаимозаменяемые обьекты по признаку Beverage
// Работаем как с обвертками
abstract class CondimentDecorator extends Beverage{
    public beverage:Beverage;
    abstract getDescription():string;// реализовать заново для всех дополнений
    getSize():number{
        return this.beverage.getSize();
    }
}

//Шоколад Моккачино
class Mocha extends CondimentDecorator{
    constructor(beverage: Beverage) {
        super();
        this.beverage = beverage;
    }
    getDescription():string{// для основных напитков описание уже реализованно
        return this.beverage.getDescription() + ", Mocha";
    }
    public cost(): number {
        let cost:number =  this.beverage.cost();
        switch (this.beverage.getSize()) {
            case Size.TALL:
                cost += 0.20;
                break;
            case Size.GRANDE:
                cost += 0.35;
                break;
            case Size.VENTI:
                cost += 0.40;
                break;
        }
        return cost;
    }
}

class Milk extends CondimentDecorator{
    constructor(beverage: Beverage) {
        super();
        this.beverage = beverage;
    }
    getDescription():string{// для основных напитков описание уже реализованно
        return this.beverage.getDescription() + ", Milk";
    }
    public cost(): number {
        let cost:number =  this.beverage.cost();
        switch (this.beverage.getSize()) {
            case Size.TALL:
                cost += 0.15;
                break;
            case Size.GRANDE:
                cost += 0.25;
                break;
            case Size.VENTI:
                cost += 0.30;
                break;
        }
        return cost;
    }
}
// Соя
class Soy extends CondimentDecorator{
    constructor(beverage: Beverage) {
        super();
        this.beverage = beverage;
    }
    getDescription():string{// для основных напитков описание уже реализованно
        return this.beverage.getDescription() + ", Soy";
    }
    public cost(): number {
        let cost:number =  this.beverage.cost();
        switch (this.beverage.getSize()) {
            case Size.TALL:
                 cost += 0.10;
                break;
            case Size.GRANDE:
                 cost += 0.15;
                break;
            case Size.VENTI:
                 cost += 0.20;
                 break;
        }
        return cost;
    }
}
//Взбитые сливки
class Whip extends CondimentDecorator{
    constructor(beverage: Beverage) {
        super();
        this.beverage = beverage;
    }
    getDescription():string{// для основных напитков описание уже реализованно
        return this.beverage.getDescription() + ", Whip";
    }
    public cost(): number {
        let cost:number =  this.beverage.cost();
        switch (this.beverage.getSize()) {
            case Size.TALL:
                cost += 0.10;
                break;
            case Size.GRANDE:
                cost += 0.15;
                break;
            case Size.VENTI:
                cost += 0.20;
                break;
        }
        return cost;
    }
}

// Кофе Espresso
let espresso: Beverage = new Espresso(Size.VENTI);
console.log(espresso.getDescription()," $",espresso.cost().toFixed(2));

//Кофе с двойным шоколадом и взбитыми сливками
let dark_roast: Beverage = new DarkRoast();
dark_roast = new Mocha(dark_roast);
dark_roast = new Mocha(dark_roast);
dark_roast = new Milk(dark_roast);
console.log(dark_roast.getDescription()," $",dark_roast.cost().toFixed(2));

// Кофе "Домашняя смесь" с соей,шоколадом и взбитыми сливками
let house_blend: Beverage = new HouseBlend(Size.VENTI);
house_blend = new Soy(house_blend);
house_blend = new Mocha(house_blend);
house_blend = new Whip(house_blend);
console.log(house_blend.getDescription()," $",house_blend.cost().toFixed(2));

// Espresso  $ 2.44
// DarkRoast, Mocha, Mocha, Milk  $ 1.55
// House Blend Coffee, Soy, Mocha, Whip  $ 2.09
