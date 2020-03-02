
//Итератор — это поведенческий паттерн, позволяющий последовательно обходить сложную коллекцию, без раскрытия деталей её реализации.

use std::iter::Iterator;
use words::WordsCollection2;

pub mod words{
   pub struct WordsCollection2{
        items:Vec<String>,
        prev:usize,
        reverse:bool
    }
    impl WordsCollection2{
        pub fn new(items:Vec<String>,reverse:bool)->Self{
            if !reverse {
                return Self{items,prev:0,reverse};
            }
            let prev = items.len();
            Self{items,prev,reverse}
        }
    }

    impl Iterator for WordsCollection2 {
        type Item = String;
        fn next(&mut self) -> Option<String> {
            if self.reverse {
                if self.prev >0{
                    self.prev-=1;
                    return Some(self.items[self.prev].clone());
                }
                return None;
            }else{
                if self.prev < self.items.len(){
                    self.prev+=1;
                    return Some(self.items[self.prev-1].clone());
                }
                return None;
            }

        }
    }
}





struct WordsCollection{
    items:Vec<String>,
}

impl std::iter::IntoIterator for WordsCollection {
    type Item = String;
    type IntoIter = ::std::vec::IntoIter<String>;

    fn into_iter(self) -> Self::IntoIter {
        self.items.into_iter()
    }
}

fn main(){
    let w = WordsCollection{items:vec!["First".into(),"Second".into(),"Third".into()]};
    for i in w {
        println!("{}",i);
    }


    let w = WordsCollection2::new(vec!["First".into(),"Second".into(),"Third".into()],true);
    for i in w {
        println!("{}",i);
    }
}
