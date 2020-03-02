

trait Drink{
    // Общий алгоритм приготовления, а отличные детали переопределенны
    //Шаблонный метод определяет скелет алгоритма.
    fn template_method(&mut self){
        self.step_cook_1();
        self.step_cook_2();
        self.step_cook_3();
        self.step_cook_4();
    }
    fn step_cook_2(&mut self);
    fn step_cook_4(&mut self);

    fn step_cook_1(&mut self){
        println!("Drink step_cook_1");
    }
    fn step_cook_3(&mut self){
        println!("Drink step_cook_3");
    }
}

struct Coffe;
impl Drink for Coffe{
    fn step_cook_2(&mut self){
        println!("Coffe step_cook_2");
    }
    fn step_cook_4(&mut self){
        println!("Coffe step_cook_4");
    }
}

struct Tea;
impl Drink for Tea{
    fn step_cook_1(&mut self){
        println!("Tea step_cook_1");
    }
    fn step_cook_2(&mut self){
        println!("Tea step_cook_2");
    }
    fn step_cook_4(&mut self){
        println!("Tea step_cook_4");
    }
}

fn cook<T:Drink>(obj:Box<&mut T>){
    obj.template_method();
    println!("");
}

fn main(){

    cook( Box::new(&mut(Tea)) );
    cook( Box::new(&mut(Coffe)) );
}

/*
Tea step_cook_1
Tea step_cook_2
Drink step_cook_3
Tea step_cook_4

Drink step_cook_1
Coffe step_cook_2
Drink step_cook_3
Coffe step_cook_4
*/