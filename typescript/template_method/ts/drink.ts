
abstract class Drink {
    // Общий алгоритм приготовления, а отличные детали переопределенны
    //Шаблонный метод определяет скелет алгоритма.
    templateMethod(){
        this.step_cook_1();
        this.step_cook_2();
        this.step_cook_3();
        this.step_cook_4();
    }
    step_cook_1(){
        console.log("Drink step_cook_1");
    }
    step_cook_3(){
        console.log("Drink step_cook_3");
    }
    abstract step_cook_2();
    abstract step_cook_4();
}

class Coffe extends Drink{
    step_cook_2(){
        console.log("Coffe step_cook_2");
    }
    step_cook_4(){
        console.log("Coffe step_cook_4");
    }
}

class Tea extends Drink{
    step_cook_1(){
        console.log("Tea step_cook_1");
    }
    step_cook_2(){
        console.log("Tea step_cook_2");
    }
    step_cook_4(){
        console.log("Tea step_cook_4");
    }
}

function cook( obj:Drink){
    obj.templateMethod();
    console.log("\n") ;
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