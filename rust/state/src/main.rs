// Состояние — это поведенческий паттерн, позволяет объектам менять поведение в зависимости от своего состояния.
// Извне создаётся впечатление, что изменился класс объекта.
//Применяется с машиной состояний(стейт-машина или конечный автомат)
//Основная идея в том, что программа может находиться в одном из нескольких состояний, которые всё время сменяют друг друга.
//Вместо того, чтобы хранить код всех состояний, первоначальный объект, называемый контекстом,
// будет содержать ссылку на один из объектов-состояний и делегировать ему работу, зависящую от состояния.


use std::marker::PhantomData;
use std::string::String;

struct SoldState;// Состояние продано
struct SoldOutState;// Состояние все продано, нет шариков
struct NoQuarterState;//Состояние монетка не внесена
struct HasQuarterState;//Состояние монетка внесена

struct GumballMashine<S>{
    count:i32,
    state: PhantomData<S>,
}

impl GumballMashine<SoldState>{
    fn realese_ball(&mut self){
        if self.count > 0 {
            self.count -= 1;
        }
    }
    fn get_count(&self)->i32{
        self.count
    }
}

impl std::fmt::Debug for GumballMashine<SoldState> {
    fn fmt(&self, f: &mut std::fmt::Formatter) -> std::fmt::Result {
        write!(f, "GumballMashine<SoldState> {{ count: {} }}", self.count)
    }
}
impl std::fmt::Debug for GumballMashine<SoldOutState> {
    fn fmt(&self, f: &mut std::fmt::Formatter) -> std::fmt::Result {
        write!(f, "GumballMashine<SoldOutState> {{ count: {} }}", self.count)
    }
}
impl std::fmt::Debug for GumballMashine<NoQuarterState> {
    fn fmt(&self, f: &mut std::fmt::Formatter) -> std::fmt::Result {
        write!(f, "GumballMashine<NoQuarterState> {{ count: {} }}", self.count)
    }
}
impl std::fmt::Debug for GumballMashine<HasQuarterState> {
    fn fmt(&self, f: &mut std::fmt::Formatter) -> std::fmt::Result {
        write!(f, "GumballMashine<HasQuarterState> {{ count: {} }}", self.count)
    }
}



//Переход из состояния монетка не внесена в состояние монетка внесена
/// NoQuarterState -- HasQuarterState
impl From<GumballMashine<NoQuarterState>> for GumballMashine<HasQuarterState> {
    fn from(_val: GumballMashine<NoQuarterState>) -> GumballMashine<HasQuarterState> {
        GumballMashine {
            count: _val.count,
            state: PhantomData,
        }
    }
}
//Переход из состояния монетка внесена в состояние монетка не внесена
/// NoQuarterState -- HasQuarterState
impl From<GumballMashine<HasQuarterState>> for GumballMashine<NoQuarterState> {
    fn from(_val: GumballMashine<HasQuarterState>) -> GumballMashine<NoQuarterState> {
        GumballMashine {
            count: _val.count,
            state: PhantomData,
        }
    }
}
//Переход из состояния монетка внесена в состояние продано
/// HasQuarterState -- SoldState
impl From<GumballMashine<HasQuarterState>> for GumballMashine<SoldState> {
    fn from(_val: GumballMashine<HasQuarterState>) -> GumballMashine<SoldState> {
        GumballMashine {
            count: _val.count,
            state: PhantomData,
        }
    }
}
//Переход из состояния продано в состояние шариков больше нет
/// SoldState -- SoldOutState
impl From<&mut GumballMashine<SoldState>> for GumballMashine<SoldOutState> {
    fn from(_val: &mut GumballMashine<SoldState>) -> GumballMashine<SoldOutState> {
        GumballMashine {
            count: _val.count,
            state: PhantomData,
        }
    }
}
//Переход из состояния продано в состояние монетка не внесена
/// SoldState -- NoQuarterState
impl From<&mut GumballMashine<SoldState>> for GumballMashine<NoQuarterState> {
    fn from(_val: &mut GumballMashine<SoldState>) -> GumballMashine<NoQuarterState> {
        GumballMashine {
            count: _val.count,
            state: PhantomData,
        }
    }
}


//--------------------------------------------------------------------------------------------------
fn new_gumball(count:i32)->GumballMashine<NoQuarterState>{
    let gumball:GumballMashine<NoQuarterState> = GumballMashine{count,state: PhantomData};
    gumball
}

fn insertQuarter(gumball:GumballMashine<NoQuarterState>)->GumballMashine<HasQuarterState>{
    println!("Монетка внесена");
    gumball.into()
}
fn ejectQuarter(gumball:GumballMashine<HasQuarterState>)->GumballMashine<NoQuarterState>{
    println!("Возврат монетки");
    gumball.into()
}
fn turnCrank(gumball:GumballMashine<HasQuarterState>)->GumballMashine<SoldState>{
    println!("Получение шарика");
    gumball.into()
}
fn dispense(gunball:&mut GumballMashine<SoldState>)->Result<GumballMashine<NoQuarterState>,GumballMashine<SoldOutState>>{
    gunball.realese_ball();
    if gunball.get_count() > 0 {
        return Ok(gunball.into());
    }else{
        return Err(gunball.into());
    }
}


fn main(){
  /*
    let gumball_no_quarter_state = new_gumball(5);
    let gumball_has_quarter_state = insertQuarter(gumball_no_quarter_state);
    //let gumball_no_quarter_state = ejectQuarter(gumball_has_quarter_state);
    let mut gumball_sold_state = turnCrank(gumball_has_quarter_state);
    let gumball  = dispense(&mut gumball_sold_state);
*/

    let gumball_no_quarter_state = new_gumball(3);
    let gumball_has_quarter_state = insertQuarter(gumball_no_quarter_state);//бросить монетку
    let mut gumball_sold_state = turnCrank(gumball_has_quarter_state);//опустить рычаг
    let gumball  = dispense(&mut gumball_sold_state);
     println!("{:?}\n",gumball_sold_state);


    let gumball_no_quarter_state = new_gumball(3);
    let gumball_has_quarter_state = insertQuarter(gumball_no_quarter_state);//бросить монетку
    let gumball_no_quarter_state = ejectQuarter(gumball_has_quarter_state);//вернуть монетку
    println!("Опустить рычаг не получиться, нет реализованной функции!");
    println!("{:?}\n",gumball_no_quarter_state);

    println!("Бросить монетку два раза не получиться, первый вызов берет владение переменной!\n");

    let gumball_no_quarter_state = new_gumball(3);
    let gumball_has_quarter_state = insertQuarter(gumball_no_quarter_state);//бросить монетку
    println!("{:?}",gumball_has_quarter_state);
}
/*
Монетка внесена
Получение шарика
GumballMashine<SoldState> { count: 2 }

Монетка внесена
Возврат монетки
Опустить рычаг не получиться, нет реализованной функции!
GumballMashine<NoQuarterState> { count: 3 }

Бросить монетку два раза не получиться, первый вызов берет владение переменной!

Монетка внесена
GumballMashine<HasQuarterState> { count: 3 }
*/









