// Запуск интерпретации в js
// $ tsc
//**********************************************************************************************************************

// Такой подход не позволит гибко вносить новые типы
// не все типы уток должны иметь реализации методов которые они получают от Duck
/*
abstract class Duck {
    abstract quack(): string;
    abstract swim(): string;
    abstract display(): string;
    abstract fly(): void;
}


export class MallardDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Duck'){
        super();
        this.name = name;
    }

    public quack(): string {
        return  `Кряк , кряк ${this.name}`;
    }

    public swim(): string {
        return `${this.name} плавает`;
    }

    public display(): string {
        return `${this.name} она Кряква - это дикая утка`;
    }
    public fly() {
        console.log(`${this.name} Кряква полетела`);
    }
}

export class RedHeadDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Duck2'){
        super();
        this.name = name;
    }

    public quack(): string {
        return  `Кряк , кряк ${this.name}`;
    }

    public swim(): string {
        return `${this.name} плавает`;
    }

    public display(): string {
        return `${this.name} она Красная голова - это Американская утка`;
    }
    public fly() {
         console.log(`${this.name} Красная голова полетела`) ;
    }
}

export class RubberDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Duck3'){
        super();
        this.name = name;
    }

    public quack(): string {
        return  `Кряк , кряк ${this.name}`;
    }

    public swim(): string {
        return `${this.name} плавает`;
    }

    public display(): string {
        return `${this.name} она резиновая утка`;
    }

    public fly() {
        throw new Error(`${this.name} резиновая утка не летает`);
    }
}

export class DecoyDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Duck4'){
        super();
        this.name = name;
    }

    public quack(): string {
        throw new Error(`${this.name} деревянная утка не крякает`);
    }

    public swim(): string {
        return `${this.name} плавает`;
    }

    public display(): string {
        return `${this.name} она из дерева`;
    }

    public fly() {
        throw new Error(`${this.name} деревянная утка не летает`);
    }
}

*/
// =====================================================================================================================
// Нужно выделить то что изменяется и инкапсулировать эти аспекты, что бы они не влияли на работу остального кода.
// Именно методы fly и quack не нужны во всех субклассах Duck. Эти аспекты поведения вынесем в отдельные классы.
// Для предоставляения разных аспектов поведения в каждом конкретном случае будем использовать интерфейс FlyBehavior и QuackBehavior

// Тереь утка из дерева не летает и не крякает, а утка из резины крякает но не летает, остальные настоящие утки и летают и крякают.
// Перенесли поведение в свойства классов, которые имеют тип интерфейса который реализуют множество классов поведения

//Паттрен СТРАТЕГИЯ
//паттерн позволяет вносить любые изменения
//задача подразумевает дальнейшее изменение путем добавления новых типов
//композиция интерфейсов поведения позволяет менять поведение в процессе поведения и инкапсулировать алгоритм

// интерфейсы поведения
export interface FlyBehaviorImpl {
    fly(): void;
}
export interface QuackBehaviorImpl {
    quack(): string;
}

// классы поведения fly
class FlyWithWings  implements FlyBehaviorImpl {
    public fly() {console.log(`Реализация полета`);}
}
class FlyNoWay  implements FlyBehaviorImpl {
    public fly() {console.log(`Утка не может летать`);}
}
// классы поведения quack
class Quack  implements QuackBehaviorImpl {
    public quack(): string {console.log(`Кряканье`);return `Кряканье`;}
}
class Squeak  implements QuackBehaviorImpl {
    public quack(): string {console.log(`Резиновые утки пищат`);return `Резиновые утки пищат`;}
}
class MuteQuack  implements QuackBehaviorImpl {
    public quack(): string {console.log(`Пустая реализация`);return `Пустая реализация кряканья`;}
}

// Базовый класс реализация композицией через интерфейс
abstract class Duck{
    public flyBehavior: FlyBehaviorImpl;
    public quackBehavior: QuackBehaviorImpl;

    abstract display(): string;

    public swim(): string {return `Все утки плавают`;}
    // Вызов у обьекта реализующего кряканье метод крякания
    public performQuack(): string{return this.quackBehavior.quack();}
    public performFly(): void{this.flyBehavior.fly();}
    public setFlyBehavior(fly: FlyBehaviorImpl):void{this.flyBehavior = fly;}
    public setQuackBehavior(quack: QuackBehaviorImpl):void{this.quackBehavior = quack;}
}

//Субклассы
// субклассы наследуются от базового класса Duck
export class MallardDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Кряква'){
        super();
        this.name = name;
        this.quackBehavior = new Quack();// класс поведения
        this.flyBehavior = new FlyWithWings();// класс поведения
    }
    public swim(): string {return `${this.name} плавает`;}
    public display(): string {return `${this.name} она Кряква - это дикая утка`;}
}
//резиновая
export class RubberDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Резина'){
        super();
        this.name = name;
        this.quackBehavior = new Squeak();
        this.flyBehavior = new FlyNoWay();
    }
    public swim(): string {return `${this.name} плавает`;}
    public display(): string {return `${this.name} она резиновая утка`;}
}
//приманка
export class DecoyDuck extends Duck{
    private readonly name:string;

    constructor(name:string='Приманка'){
        super();
        this.name = name;
        this.quackBehavior = new MuteQuack();
        this.flyBehavior = new FlyNoWay();
    }
    public swim(): string {return `${this.name} плавает`;}
    public display(): string {return `${this.name} она из дерева`;}
}

//Имитация утиного кряка (класс не является наследником Duck но методы работы с поведением можно просто скопировать)
export class Manok {
    private quackBehavior: QuackBehaviorImpl;
    constructor(quack: QuackBehaviorImpl){
        this.quackBehavior = quack;// класс поведения
    }
    public performQuack(): string{return this.quackBehavior.quack();}
    public setQuackBehavior(quack: QuackBehaviorImpl):void{this.quackBehavior = quack;}
}

// Создаем реальную утку из рода Крякв
let duck = new MallardDuck();
duck.performFly();// по умолчанию она летает
duck.setFlyBehavior(new FlyWithWings());//изменяем ее поведение на не летает
duck.performFly();// теперь она не летает

duck.performQuack();// по умолчанию она крякает
duck.setQuackBehavior(new Squeak());//изменяем ее поведение на писк резиновой утки
duck.performQuack();// теперь она пищит

let manok = new Manok(new Quack());
manok.performQuack();