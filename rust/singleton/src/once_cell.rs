use once_cell::sync::Lazy; // 1.3.1
use std::sync::Mutex;

static ARRAY: Lazy<Mutex<Vec<u8>>> = Lazy::new(|| Mutex::new(vec![]));

fn do_a_call() {
    ARRAY.lock().unwrap().push(1);
}

// cargo run --bin once_cell
fn main() {
    do_a_call();
    do_a_call();
    do_a_call();

    println!("called {}", ARRAY.lock().unwrap().len());
}