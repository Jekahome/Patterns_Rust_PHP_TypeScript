
// Декоратор часто используется в связке с Фабрикой или Строителем

// Для возможности создавать разнообразные комбинации(матрешки) добавок при поддержке общего интерфейса
#[derive(Clone,Copy)]
enum Size{TALL,GRANDE, VENTI}
impl Default for Size {
    fn default() -> Size { Size::TALL }
}


// Напиток
trait Beverage{
    fn get_description(&self)->String;
    fn set_size(&mut self,size:Size);
    fn get_size(&self)->Size;
    fn cost(&self)->f32;

}


// Виды напитка ------------------------------------------------------------------------------------
struct HouseBlend{
    size:Size,
    description:String,
    default_cost:f32
}
impl Default for HouseBlend {
    fn default() -> Self {
        Self{
            size:Size::default(),
            description:String::from("House Blend Coffee"),
            default_cost:0.89f32 }
    }
}
impl HouseBlend{
    fn new(size:Size,default_cost:f32,description:String)->Self{
        Self{size,description,default_cost}
    }
    fn new_with_size_default(size:Size)->Self{
        let def = Self::default();
        Self{size,description:def.description,default_cost:def.default_cost}
    }
}
impl Beverage for HouseBlend{
    fn get_description(&self)->String{
        self.description.clone()
    }
    fn set_size(&mut self,size:Size){
        self.size = size;
    }
    fn get_size(&self)->Size{
        self.size
    }
    fn cost(&self)->f32{
       match self.get_size() {
              Size::TALL => self.default_cost,
              Size::GRANDE =>  self.default_cost+0.35,
              Size::VENTI =>  self.default_cost+0.40,
              _=> self.default_cost
        }
    }
}


struct DarkRoast{
    size:Size,
    description:String,
    default_cost:f32
}
impl Default for DarkRoast {
    fn default() -> Self {
        Self{
            size:Size::default(),
            description:String::from("DarkRoast"),
            default_cost:1.0f32 }
    }
}
impl DarkRoast{
    fn new(size:Size,default_cost:f32,description:String)->Self{
        Self{size,description,default_cost}
    }
    fn new_with_size_default(size:Size)->Self{
        let def = Self::default();
        Self{size,description:def.description,default_cost:def.default_cost}
    }
}
impl Beverage for DarkRoast{
    fn get_description(&self)->String{
        self.description.clone()
    }
    fn set_size(&mut self,size:Size){
        self.size = size;
    }
    fn get_size(&self)->Size{
        self.size
    }
    fn cost(&self)->f32{
        match self.get_size() {
            Size::TALL => self.default_cost,
            Size::GRANDE =>  0.35,
            Size::VENTI =>  0.40,
            _=> self.default_cost
        }
    }
}


struct Espresso{
    size:Size,
    description:String,
    default_cost:f32
}
impl Default for Espresso {
    fn default() -> Self {
        Self{
            size:Size::default(),
            description:String::from("Espresso"),
            default_cost:1.99f32 }
    }
}
impl Espresso{
    fn new(size:Size,default_cost:f32,description:String)->Self{
        Self{size,description,default_cost}
    }
    fn new_with_size_default(size:Size)->Self{
        let def = Self::default();
        Self{size,description:def.description,default_cost:def.default_cost}
    }
}
impl Beverage for Espresso{
    fn get_description(&self)->String{
        self.description.clone()
    }
    fn set_size(&mut self,size:Size){
        self.size = size;
    }
    fn get_size(&self)->Size{
        self.size
    }
    fn cost(&self)->f32{
        match self.get_size() {
            Size::TALL => self.default_cost,
            Size::GRANDE =>  self.default_cost+0.40,
            Size::VENTI =>  self.default_cost+0.45,
            _=> self.default_cost
        }
    }
}


struct Decaf{
    size:Size,
    description:String,
    default_cost:f32
}
impl Default for Decaf {
    fn default() -> Self {
        Self{
            size:Size::default(),
            description:String::from("Decaf"),
            default_cost:1.99f32 }
    }
}
impl Decaf{
    fn new(size:Size,default_cost:f32,description:String)->Self{
        Self{size,description,default_cost}
    }
}
impl Beverage for Decaf{
    fn get_description(&self)->String{
        self.description.clone()
    }
    fn set_size(&mut self,size:Size){
        self.size = size;
    }
    fn get_size(&self)->Size{
        self.size
    }
    fn cost(&self)->f32{
        match self.get_size() {
            Size::TALL => self.default_cost,
            Size::GRANDE =>  self.default_cost+0.35,
            Size::VENTI =>  self.default_cost+0.40,
            _=> self.default_cost
        }
    }
}




trait New{
    fn new(beverage:Box<dyn Beverage>)->Self;
}

// Дополнения к напитку ----------------------------------------------------------------------------
struct Mocha{
    beverage:Box<dyn Beverage>,
}
impl New for Mocha{
    fn new(beverage:Box<dyn Beverage>)->Self{
        Self{beverage}
    }
}
impl Beverage for Mocha{
    fn get_description(&self)->String{
        format!("{}, Mocha",self.beverage.get_description())
    }
    fn set_size(&mut self,size:Size){
        self.beverage.set_size(size);
    }
    fn get_size(&self)->Size{
        self.beverage.get_size()
    }
    fn cost(&self)->f32{
        let mut cost:f32 =  self.beverage.cost();
        cost += match self.beverage.get_size() {
            Size::TALL =>  0.20,
            Size::GRANDE =>  0.35,
            Size::VENTI =>  0.40,
            _=>  0.20
        };
        cost
    }
}

struct Milk{
    beverage:Box<dyn Beverage>,
}
impl New for Milk{
    fn new(beverage:Box<dyn Beverage>)->Self{
        Self{beverage}
    }
}
impl Beverage for Milk{
    fn get_description(&self)->String{
        format!("{}, Milk",self.beverage.get_description())
    }
    fn set_size(&mut self,size:Size){
        self.beverage.set_size(size);
    }
    fn get_size(&self)->Size{
        self.beverage.get_size()
    }
    fn cost(&self)->f32{
        let mut cost:f32 =  self.beverage.cost();
        cost += match self.beverage.get_size() {
            Size::TALL =>  0.15,
            Size::GRANDE =>  0.25,
            Size::VENTI =>  0.30,
            _=>  0.15
        };
        cost
    }
}

struct Soy{
    beverage:Box<dyn Beverage>,
}
impl New for Soy{
    fn new(beverage:Box<dyn Beverage>)->Self{
        Self{beverage}
    }
}
impl Beverage for Soy{
    fn get_description(&self)->String{
        format!("{}, Soy",self.beverage.get_description())
    }
    fn set_size(&mut self,size:Size){
        self.beverage.set_size(size);
    }
    fn get_size(&self)->Size{
        self.beverage.get_size()
    }
    fn cost(&self)->f32{
        let mut cost:f32 =  self.beverage.cost();
        cost += match self.beverage.get_size() {
            Size::TALL =>  0.10,
            Size::GRANDE =>  0.15,
            Size::VENTI =>  0.20,
            _=>  0.10
        };
        cost
    }
}

struct Whip{
    beverage:Box<dyn Beverage>,
}
impl New for Whip{
    fn new(beverage:Box<dyn Beverage>)->Self{
        Self{beverage}
    }
}
impl Beverage for Whip{
    fn get_description(&self)->String{
        format!("{}, Whip",self.beverage.get_description())
    }
    fn set_size(&mut self,size:Size){
        self.beverage.set_size(size);
    }
    fn get_size(&self)->Size{
        self.beverage.get_size()
    }
    fn cost(&self)->f32{
        let mut cost:f32 =  self.beverage.cost();
        cost += match self.beverage.get_size() {
            Size::TALL =>  0.10,
            Size::GRANDE =>  0.15,
            Size::VENTI =>  0.20,
            _=>  0.10
        };
        cost
    }
}

fn add<T:Beverage + New>(beverage:Box<dyn Beverage>)->T{
    T::new(beverage)
}

fn main(){

    // Кофе Espresso
    let espresso:Espresso = Espresso::new_with_size_default(Size::VENTI);
    println!("{:?} ${:?}",espresso.get_description(),math::round::floor(espresso.cost().into(),2));

    //Кофе с двойным шоколадом и взбитыми сливками
    let dark_roast:DarkRoast = DarkRoast::default();
    let mocha = add::<Mocha>(Box::new(dark_roast));
    let mocha = add::<Mocha>(Box::new(mocha));
    let milk = add::<Milk>(Box::new(mocha));
    println!("{:?} ${:?}",milk.get_description(),math::round::floor(milk.cost().into(),2));

    // Кофе "Домашняя смесь" с соей,шоколадом и взбитыми сливками
    let house_blend:HouseBlend = HouseBlend::new(Size::VENTI,0.89,String::from("HouseBlend"));
    let soy = add::<Soy>(Box::new(house_blend));
    let mocha = add::<Mocha>(Box::new(soy));
    let whip = add::<Whip>(Box::new(mocha));
    println!("{:?} ${:?}",whip.get_description(),math::round::floor(whip.cost().into(),2));

    /*
    "Espresso" $2.44
    "DarkRoast, Mocha, Mocha, Milk" $1.55
    "HouseBlend, Soy, Mocha, Whip" $2.08
    */

}