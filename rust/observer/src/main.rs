//use std::sync::{RwLock, RwLockWriteGuard};
use std::rc::Rc;
use std::collections::HashMap;
use std::option::Option;
use uuid::Uuid;
use std::cell::{RefCell, Ref};
use rand::prelude::*;

//use std::borrow::{BorrowMut, Borrow}; не работает если подключить

// Есть вариант с каналами, оповещение подписчиков по каналу std::sync::mpsc::channel
// или crossbeam_channel для нескольких отправителей и приемников и гарантии жизни

// Есть вариант не хранить Uuid подписчика,а хранить ссылку на функцию  Vec<Box<dyn Fn(&E)>>
// https://stackoverflow.com/questions/37572734/how-can-i-implement-the-observer-pattern-in-rust

// Вариант с хранением ссыдки на субьект в каждом подписчике не жизнеспособен,
// нужно хранить субьект через слабый указатель Weak и при изменчивом заимствовании не сможем взять
// еще любую другую ссылку на подписчика так как получается циклическая ссылка


// Использую Rc<RefCell<Box<dyn ...>>> для возможности раскидать ссылки (Rc),
// возможность выборочно брать изменчивое/не изменчивое заимствование (RefCell),
// для пооддержки определенного интерфейса Box<dyn ...>

trait SubjectImpl
{
    fn register_observer(&mut self,obs:Rc<RefCell<Box<dyn ObserverImpl>>>) -> Option<Uuid>;
    fn remove_observer(&mut self,key:Uuid);
    fn notify_observers(&mut self);
    fn set_measurements(&mut self, temperature:f32, humidity:f32, pressure:i32);// фейковая загрузка
}

trait DataImpl
{
    fn get_temperature(&self) -> f32;
    fn get_humidity(&self) -> f32;
    fn get_pressure(&self) -> i32;
    fn set_temperature(&mut self,temperature:f32);
    fn set_humidity(&mut self,humidity:f32);
    fn set_pressure(&mut self,pressure:i32);
}

trait ObserverImpl
{
    fn update(&mut self,data:Ref<Box<dyn DataImpl>>);// Ref получение данных от интерфейса DataImpl по не изменяемой ссылке
    fn get_key(&self) -> Uuid;
    fn set_key(&mut self,key:Uuid);
    fn remove_observer(&mut self);
    fn get_subscription(&self)->bool;
}

trait DisplayElementImpl
{
    fn display(&self);
}

#[derive(Default)]
struct Data{
    temperature:f32,
    humidity:f32,//влажность
    pressure:i32,//давление
}
// Реализация запроса состояния
impl DataImpl for Data{
    fn get_temperature(&self) -> f32{
        self.temperature
    }
    fn get_humidity(&self) -> f32{
        self.humidity
    }
    fn get_pressure(&self) -> i32{
        self.pressure
    }
    fn set_temperature(&mut self,temperature:f32){
        self.temperature = temperature;
    }
    fn set_humidity(&mut self,humidity:f32){
        self.humidity = humidity;
    }
    fn set_pressure(&mut self,pressure:i32){
        self.pressure = pressure;
    }
}
// Управляющая структура, логика
struct WeatherData{
     observers: HashMap<Uuid, Rc<RefCell<Box<dyn ObserverImpl>>>>,
     data:Rc<RefCell<Box<dyn DataImpl>>>,
     changed:bool
}

impl WeatherData{
    pub fn constructor(data:Rc<RefCell<Box<dyn DataImpl>>>) -> Self {
        WeatherData{observers:HashMap::new(),data: data,changed:false}
    }

    fn set_changed(&mut self){
        self.changed = true;
    }

    fn clear_changed(&mut self){
        self.changed = false;
    }

    pub fn has_changed(&self) -> bool{
        self.changed
    }

    // Оповещение наблюдателей о появлении новых данных
    fn measurements_changed(&mut self){
        self.set_changed(); // Если новые данные не значительные можно не оповещать
        self.notify_observers();
    }
    // удалить отписавшихся подписчиков
    fn update_active_observer(&mut self){
        self.observers.retain(|_, v| v.borrow().get_subscription());
    }

}

impl SubjectImpl for WeatherData{
    fn register_observer(&mut self,obs:Rc<RefCell<Box<dyn ObserverImpl>>>) -> Option<Uuid>{
        if !self.observers.contains_key(&obs.borrow().get_key()){
            let key = Uuid::new_v5(&Uuid::NAMESPACE_OID,"Observer".as_bytes());
            self.observers.insert(key, obs);
           return Some(key);
        }
        None
    }
    fn remove_observer(&mut self,key:Uuid){
        if self.observers.contains_key(&key){
            self.observers.remove(&key);
            println!("key {:?} remove_observer",&key);
            //self.observers.retain(|x| x != &obs);
        }
    }
    fn notify_observers(&mut self){
        self.update_active_observer();
        if self.has_changed() {
            /*let mut obs = &mut self.observers;
            obs.iter_mut().map(|el|{
                el.update();
            });*/

            /*for el in self.observers.values_mut() {
                //&mut Rc<RefCell<Box<dyn ObserverImpl>>>
               el.borrow_mut().update();
            }*/
            for (_, el) in self.observers.iter_mut() {
                el.borrow_mut().update(self.data.borrow());

            }
            /*let iter = &self.observers.iter_mut();
            iter.map(| el|{
                el.update(&self);
            });*/
            /*for mut el in self.observers.into_iter(){
                el.update(&self);
            }*/
            self.clear_changed();
        }
    }
    // Загрузка фейковых данных метеостанции с оповещением
    fn set_measurements(&mut self, temperature:f32, humidity:f32, pressure:i32) {
        {
            let mut data = self.data.borrow_mut();
            data.set_temperature(temperature);
            data.set_humidity(humidity);
            data.set_pressure(pressure);
        }
        // избавились от мутабельного заимствования
        self.measurements_changed();
    }
}



#[derive(Clone)]
struct CurrentConditionsDisplay{
    temperature:f32,
    humidity:f32,//влажность
    pressure:i32,//давление
    key:Uuid,
    subscription:bool
}
impl CurrentConditionsDisplay{
    pub fn constructor() -> Self {
        CurrentConditionsDisplay{temperature:0.0,humidity:0.0,pressure:0 ,key:Default::default(),subscription:false}
    }
}



impl ObserverImpl for CurrentConditionsDisplay{
    fn update(&mut self,weather_data:Ref<Box<dyn DataImpl>>){
        if self.subscription {
            //weatherData.set_humidity(1f32); не получиться так сделать
            self.temperature = weather_data.get_temperature();
            self.humidity = weather_data.get_humidity();

            self.display();
        }
    }
    fn get_key(&self)->Uuid{
        self.key
    }
    fn set_key(&mut self,key:Uuid){
        self.key = key;
        self.subscription = true;
    }
    fn remove_observer(&mut self){
        //self.weatherData.upgrade().unwrap().borrow_mut().remove_observer( self.get_key());
        self.subscription = false; //ПРИВЕСТИ В ПОРЯДОК СПОСОБ ОТКАЗА ОТ ПОДПИСКИ
    }
    fn get_subscription(&self)->bool{
        self.subscription
    }
}

impl DisplayElementImpl for CurrentConditionsDisplay{
    fn display(&self){
        println!("Текущие условия {:?}:",self.get_key());
        println!("{:?}F degrees and, {:?}% humidity",self.temperature, self.humidity);
        println!("-----------------------------------");
    }
}



fn main(){
    let data:Rc<RefCell<Box<dyn DataImpl>>> = Rc::new(RefCell::new(Box::new(Data::default())));
    let weather_data:Box<dyn SubjectImpl> =  Box::new(WeatherData::constructor(data));
    let weather_data_rc:Rc<RefCell<Box<dyn SubjectImpl>>> =  Rc::new(RefCell::new(weather_data));


    let current_conditions_display = CurrentConditionsDisplay::constructor();//
    let current_conditions_display_rc =  Rc::new(RefCell::new(Box::new(current_conditions_display.clone() ) as Box<dyn ObserverImpl> ));


    //println!("key default:{:?}",conRc.borrow().get_key());
    let key:Option<Uuid> = weather_data_rc.borrow_mut().register_observer( Rc::clone(&current_conditions_display_rc));
    if let Some(key) = key{
        //println!("key:{:?}",key);
        current_conditions_display_rc.borrow_mut().set_key(key);
    }
    //println!("key new:{:?}",conRc.borrow().get_key());

     let mut rng = thread_rng();
    weather_data_rc.borrow_mut().set_measurements(rng.gen_range(10.0, 100.0),rng.gen_range(10.0, 100.0),rng.gen_range(400, 800));

    current_conditions_display_rc.borrow_mut().remove_observer();
    weather_data_rc.borrow_mut().set_measurements(rng.gen_range(10.0, 100.0),rng.gen_range(10.0, 100.0),rng.gen_range(400, 800));


}