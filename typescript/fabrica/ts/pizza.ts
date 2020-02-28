
// Суть в инкапсуляции создания конкретных типов обьектов
//Абстрактная фабрика как следсвие уменьшения зависимости от фабрики шаблона Фабрика

enum PizzaType{CHEESE,PEPPERONI};

// Классы создатели
abstract class PizzaStore{
    orderPizza(type:PizzaType):Pizza{
        let pizza:Pizza = this.createPizza(type);
        pizza.prepare();
        pizza.bake();
        pizza.cut();
        pizza.box();
        return pizza;
    }
    abstract createPizza(type:PizzaType):Pizza;
}

class ChicagoPizzaStore extends PizzaStore{
    createPizza(type:PizzaType):Pizza{
        if(type == PizzaType.CHEESE){
            return new ChicagoStyleSheesePizza();
        }else{
            return new ChicagoStylePepperoniPizza();
        }
    }
}
class NYPizzaStore extends PizzaStore{
    createPizza(type:PizzaType):Pizza{
        if(type == PizzaType.CHEESE){
            return new NYStyleSheesePizza();
        }else{
            return new NYStylePepperoniPizza();
        }
    }
}

// Классы продукты
abstract class Pizza {
    name:string;
    prepare(){
        console.log("prepare "+this.name+"\n");
    }
    bake(){
        console.log("bake "+this.name+"\n");
    }
    cut(){
        console.log("cut "+this.name+"\n");
    }
    box(){
        console.log("box "+this.name+"\n");
    }
}



class ChicagoStyleSheesePizza extends Pizza{
    constructor(){
        super();
        this.name="ChicagoStyleSheesePizza";
    }
}
class ChicagoStylePepperoniPizza extends Pizza{
    constructor(){
        super();
        this.name="ChicagoStylePepperoniPizza";
    }
}
class NYStyleSheesePizza extends Pizza{
    constructor(){
        super();
        this.name="NYStyleSheesePizza";
    }
}
class NYStylePepperoniPizza extends Pizza{
    constructor(){
        super();
        this.name="NYStylePepperoniPizza";
    }
}



let ny_factory:PizzaStore = new NYPizzaStore();
let pizza:Pizza = ny_factory.orderPizza(PizzaType.CHEESE);
    pizza = ny_factory.orderPizza(PizzaType.PEPPERONI);

let chicago_factory:PizzaStore  = new ChicagoPizzaStore();
    pizza = chicago_factory.orderPizza(PizzaType.CHEESE);
    pizza = chicago_factory.orderPizza(PizzaType.PEPPERONI);

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