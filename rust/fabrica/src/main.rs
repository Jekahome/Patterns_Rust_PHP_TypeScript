
// Суть в инкапсуляции создания конкретных типов обьектов
//Абстрактная фабрика как следсвие уменьшения зависимости от фабрики шаблона Фабрика

#[derive(Clone,Copy)]
enum PizzaType{CHEESE,PEPPERONI}

// Классы создатели --------------------------------------------------------------------------------
trait PizzaStore{
    fn order_pizza(&mut self,types:PizzaType)->Box<dyn Pizza>{
        let mut pizza:Box<dyn Pizza> = self.create_pizza(types);
        pizza.prepare();
        pizza.bake();
        pizza.cut();
        pizza.boxes();
        pizza
    }
    fn create_pizza(&self,types:PizzaType)->Box<dyn Pizza>;
}

struct ChicagoPizzaStore;
impl  PizzaStore for ChicagoPizzaStore{
    fn create_pizza(&self,types:PizzaType)->Box<dyn Pizza>{
        match types  {
            PizzaType::CHEESE =>
                Box::new(ChicagoStyleSheesePizza::default()),
            PizzaType::PEPPERONI =>
                Box::new(ChicagoStylePepperoniPizza::default())
        }
    }
}

struct NYPizzaStore;
impl  PizzaStore for NYPizzaStore{
    fn create_pizza(&self,types:PizzaType)->Box<dyn Pizza>{
        match types  {
            PizzaType::CHEESE =>
                Box::new(NYStyleSheesePizza::default()),
            PizzaType::PEPPERONI =>
                Box::new(NYStylePepperoniPizza::default())
        }
    }
}



// Классы продукты ---------------------------------------------------------------------------------
trait Pizza{
    fn prepare(&mut self){
        println!("prepare {}",self.get_name());
    }
    fn bake(&mut self){
        println!("bake {}",self.get_name());
    }
    fn cut(&mut self){
        println!("cut {}",self.get_name());
    }
    fn boxes(&mut self){
        println!("box {}",self.get_name());
    }
    fn get_name(&self)->String;
}

struct ChicagoStyleSheesePizza{
    name:String,
}
impl Default for ChicagoStyleSheesePizza {
    fn default() -> Self {
        Self{name:String::from("ChicagoStyleSheesePizza"), }
    }
}
impl Pizza for ChicagoStyleSheesePizza{
    fn get_name(&self)->String{
        self.name.clone()
    }
}

struct ChicagoStylePepperoniPizza{
    name:String,
}
impl Default for ChicagoStylePepperoniPizza {
    fn default() -> Self {
        Self{name:String::from("ChicagoStylePepperoniPizza"), }
    }
}
impl Pizza for ChicagoStylePepperoniPizza{
    fn get_name(&self)->String{
        self.name.clone()
    }
}

struct NYStyleSheesePizza{
    name:String,
}
impl Default for NYStyleSheesePizza {
    fn default() -> Self {
        Self{name:String::from("NYStyleSheesePizza"), }
    }
}
impl Pizza for NYStyleSheesePizza{
    fn get_name(&self)->String{
        self.name.clone()
    }
}

struct NYStylePepperoniPizza{
    name:String,
}
impl Default for NYStylePepperoniPizza {
    fn default() -> Self {
        Self{name:String::from("NYStylePepperoniPizza"), }
    }
}
impl Pizza for NYStylePepperoniPizza{
    fn get_name(&self)->String{
        self.name.clone()
    }
}


fn new_pizza(p:&mut Box<dyn PizzaStore>,types:PizzaType)->Box<dyn Pizza>{
    p.order_pizza(types)
}

fn new_pizza_1<T: PizzaStore>(p:&mut T,types: PizzaType)->Box<dyn Pizza>{
    p.order_pizza(types)
}
fn new_pizza_2(p:&mut impl PizzaStore,types: PizzaType)->Box<dyn Pizza>{
    p.order_pizza(types)
}


fn main(){
    //let mut fabric_chicago:Box<PizzaStore> = Box::new(ChicagoPizzaStore);
    //let pizza = new_pizza(&mut fabric_chicago,PizzaType::CHEESE);

    let mut pizza = new_pizza(&mut (Box::new(NYPizzaStore) as Box<PizzaStore>),PizzaType::CHEESE);
            pizza = new_pizza(&mut (Box::new(NYPizzaStore) as Box<PizzaStore>),PizzaType::PEPPERONI);

            pizza = new_pizza(&mut (Box::new(ChicagoPizzaStore) as Box<PizzaStore>),PizzaType::CHEESE);
            pizza = new_pizza(&mut (Box::new(ChicagoPizzaStore) as Box<PizzaStore>),PizzaType::PEPPERONI);



/*
    let mut pizza:Box<dyn Pizza> = new_pizza_1(&mut NYPizzaStore,PizzaType::CHEESE);
    pizza  = new_pizza_1(&mut NYPizzaStore,PizzaType::PEPPERONI);
    pizza  = new_pizza_1(&mut ChicagoPizzaStore,PizzaType::CHEESE);
    pizza  = new_pizza_1(&mut ChicagoPizzaStore,PizzaType::PEPPERONI);

    let mut pizza:Box<dyn Pizza> = new_pizza_2(&mut NYPizzaStore,PizzaType::CHEESE);
    pizza = new_pizza_2(&mut NYPizzaStore,PizzaType::PEPPERONI);
    pizza  = new_pizza_2(&mut ChicagoPizzaStore,PizzaType::CHEESE);
    pizza  = new_pizza_2(&mut ChicagoPizzaStore,PizzaType::PEPPERONI);
*/
}

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

