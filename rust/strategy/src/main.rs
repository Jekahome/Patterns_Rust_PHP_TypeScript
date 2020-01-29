
// интерфейсы поведения
trait FlyBehaviorImpl{
    fn fly(&self);
}
trait QuackBehaviorImpl{
    fn quack(&self)->String;
}

// классы поведения fly
struct  FlyWithWings;
impl FlyBehaviorImpl for FlyWithWings {
    fn fly(&self) { println!("Реализация полета");}
}

struct  FlyNoWay;
impl FlyBehaviorImpl for FlyNoWay{
    fn fly(&self) { println!("Утка не может летать");}
}

// классы поведения quack
struct  Quack;
impl QuackBehaviorImpl for Quack{
    fn quack(&self)->String { String::from("Кряканье") }
}

struct  Squeak;
impl QuackBehaviorImpl for Squeak{
    fn quack(&self)->String { String::from("Резиновые утки пищат") }
}

struct  MuteQuack;
impl QuackBehaviorImpl for MuteQuack{
    fn quack(&self)->String { String::from("Пустая реализация кряканья") }
}


// Интерфейс(трейт)
trait Duck{
    fn display(&self)-> String;
    fn swim(&self)-> String{String::from("Все утки плавают")}
    fn performQuack(&self)-> String;
    fn performFly(&self);
    fn setFlyBehavior(&mut self,fly: Box<dyn FlyBehaviorImpl>);
    fn setQuackBehavior(&mut self,quack: Box<dyn QuackBehaviorImpl>);
}

//Субклассы
// субклассы наследуются от базового класса Duck
struct MallardDuck {
   name:String,
   flyBehavior: Box<dyn FlyBehaviorImpl>,
   quackBehavior: Box<dyn QuackBehaviorImpl>
}
impl Duck for MallardDuck {

    fn swim(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" плавает");
        buf.clone()
    }
    fn display(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" она Кряква - это дикая утка");
        buf.clone()
    }
    fn performQuack(&self)-> String{
        self.quackBehavior.quack().clone()
    }
    fn performFly(&self){
        self.flyBehavior.fly();
    }
    fn setFlyBehavior(&mut self,fly: Box<dyn FlyBehaviorImpl>){
        self.flyBehavior = fly;
    }
    fn setQuackBehavior(&mut self,quack: Box<dyn QuackBehaviorImpl>){
        self.quackBehavior = quack;
    }
}
impl MallardDuck {
    fn constructor(name:String,
                   flyBehavior: Box<dyn FlyBehaviorImpl>,
                   quackBehavior: Box<dyn QuackBehaviorImpl>)->Self{

        MallardDuck{name,flyBehavior,quackBehavior}
    }
    fn constructor2(name:String)->Self{
        MallardDuck{name,flyBehavior:Box::new(FlyWithWings),quackBehavior:Box::new(Quack)}
    }
}


//резиновая
struct RubberDuck {
    name:String,
    flyBehavior: Box<dyn FlyBehaviorImpl>,
    quackBehavior: Box<dyn QuackBehaviorImpl>
}
impl Duck for RubberDuck {

    fn swim(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" плавает");
        buf.clone()
    }
    fn display(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" она резиновая утка");
        buf.clone()
    }
    fn performQuack(&self)-> String{
        self.quackBehavior.quack().clone()
    }
    fn performFly(&self){
        self.flyBehavior.fly();
    }
    fn setFlyBehavior(&mut self,fly: Box<dyn FlyBehaviorImpl>){
        self.flyBehavior = fly;
    }
    fn setQuackBehavior(&mut self,quack: Box<dyn QuackBehaviorImpl>){
        self.quackBehavior = quack;
    }
}
impl RubberDuck {
    fn constructor(name:String)->Self{
        RubberDuck{name,flyBehavior:Box::new(FlyWithWings),quackBehavior:Box::new(Quack)}
    }
}

//приманка
struct DecoyDuck {
    name:String,
    flyBehavior: Box<dyn FlyBehaviorImpl>,
    quackBehavior: Box<dyn QuackBehaviorImpl>
}
impl Duck for DecoyDuck {

    fn swim(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" плавает");
        buf.clone()
    }
    fn display(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" она из дерева");
        buf.clone()
    }
    fn performQuack(&self)-> String{
        self.quackBehavior.quack().clone()
    }
    fn performFly(&self){
        self.flyBehavior.fly();
    }
    fn setFlyBehavior(&mut self,fly: Box<dyn FlyBehaviorImpl>){
        self.flyBehavior = fly;
    }
    fn setQuackBehavior(&mut self,quack: Box<dyn QuackBehaviorImpl>){
        self.quackBehavior = quack;
    }
}
impl DecoyDuck {
    fn constructor(name:String)->Self{
        DecoyDuck{name,flyBehavior:Box::new(FlyWithWings),quackBehavior:Box::new(Quack)}
    }
}

//Имитация утиного кряка т.е. требуется только поведение кряканья
struct Manok {
    name:String,
    quackBehavior: Box<dyn QuackBehaviorImpl>
}
impl Manok {
    fn constructor(name:String)->Self{
        Manok{name,quackBehavior:Box::new(Quack)}
    }
    fn swim(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" плавает");
        buf.clone()
    }
    fn display(&self)-> String{
        let mut buf = &mut self.name.clone();
        buf.push_str(" она из дерева");
        buf.clone()
    }
    fn performQuack(&self)-> String{
        self.quackBehavior.quack().clone()
    }
    fn setQuackBehavior(&mut self,quack: Box<dyn QuackBehaviorImpl>){
        self.quackBehavior = quack;
    }
}



fn main(){

    let mut m_duck = MallardDuck::constructor(String::from("Кряква"),
                                          Box::new(FlyWithWings),
                                          Box::new(Quack));
    m_duck = MallardDuck::constructor2(String::from("Кряква"));
    m_duck.performFly();
    m_duck.setFlyBehavior(Box::new(FlyNoWay));
    m_duck.performFly();

    println!("{}",m_duck.performQuack());
    m_duck.setQuackBehavior(Box::new(MuteQuack));
    println!("{}",m_duck.performQuack());
}