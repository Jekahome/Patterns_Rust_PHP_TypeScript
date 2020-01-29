"use strict";
Object.defineProperty(exports, "__esModule", { value: true });
class FlyWithWings {
    fly() { console.log(`Реализация полета`); }
}
class FlyNoWay {
    fly() { console.log(`Утка не может летать`); }
}
class Quack {
    quack() { console.log(`Кряканье`); return `Кряканье`; }
}
class Squeak {
    quack() { console.log(`Резиновые утки пищат`); return `Резиновые утки пищат`; }
}
class MuteQuack {
    quack() { console.log(`Пустая реализация`); return `Пустая реализация кряканья`; }
}
class Duck {
    swim() { return `Все утки плавают`; }
    performQuack() { return this.quackBehavior.quack(); }
    performFly() { this.flyBehavior.fly(); }
    setFlyBehavior(fly) { this.flyBehavior = fly; }
    setQuackBehavior(quack) { this.quackBehavior = quack; }
}
class MallardDuck extends Duck {
    constructor(name = 'Кряква') {
        super();
        this.name = name;
        this.quackBehavior = new Quack();
        this.flyBehavior = new FlyWithWings();
    }
    swim() { return `${this.name} плавает`; }
    display() { return `${this.name} она Кряква - это дикая утка`; }
}
exports.MallardDuck = MallardDuck;
class RubberDuck extends Duck {
    constructor(name = 'Резина') {
        super();
        this.name = name;
        this.quackBehavior = new Squeak();
        this.flyBehavior = new FlyNoWay();
    }
    swim() { return `${this.name} плавает`; }
    display() { return `${this.name} она резиновая утка`; }
}
exports.RubberDuck = RubberDuck;
class DecoyDuck extends Duck {
    constructor(name = 'Приманка') {
        super();
        this.name = name;
        this.quackBehavior = new MuteQuack();
        this.flyBehavior = new FlyNoWay();
    }
    swim() { return `${this.name} плавает`; }
    display() { return `${this.name} она из дерева`; }
}
exports.DecoyDuck = DecoyDuck;
class Manok {
    constructor(quack) {
        this.quackBehavior = quack;
    }
    performQuack() { return this.quackBehavior.quack(); }
    setQuackBehavior(quack) { this.quackBehavior = quack; }
}
exports.Manok = Manok;
let duck = new MallardDuck();
duck.performFly();
duck.setFlyBehavior(new FlyWithWings());
duck.performFly();
duck.performQuack();
duck.setQuackBehavior(new Squeak());
duck.performQuack();
let manok = new Manok(new Quack());
manok.performQuack();
