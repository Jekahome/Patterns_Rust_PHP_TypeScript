

trait Cook{
    fn cook(&mut self)->bool;
}

struct Milk{
    val:i16
}
impl Milk{
    fn new(val:i16)->Self{
       Self{val}
    }
}
impl Cook for Milk{
    fn cook(&mut self)->bool{
        println!("Cook milk, 5 minutes passed");
        return true;
    }
}

struct Groats{
    weight:i16
}
impl Groats{
    fn new(weight:i16)->Self{
        Self{weight}
    }
}
impl Cook for Groats{
    fn cook(&mut self)->bool{
        println!("Cook Groats, 5 minutes passed");
        return true;
    }
}

struct Semolina{
    ingredients:Vec<Box<dyn Cook>>,
    val:i16
}
impl Semolina{
    fn new(val:i16)->Self{
        Self{ingredients:vec![],val}
    }
    fn add(&mut self,ing:Box<dyn Cook>){
        self.ingredients.push(ing);
    }
    fn cook(&mut self){
        let mut milk = Milk::new(1);
        milk.cook();
        self.add(Box::new(milk));

        let mut gr = Groats::new(1);
        gr.cook();
        self.add(Box::new(gr));
        println!("Semolina is ready");
    }
}

fn main(){
    &mut (Semolina::new(5)).cook();
}