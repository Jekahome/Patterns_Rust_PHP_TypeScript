use std::sync::atomic::{AtomicUsize, Ordering};

static CALL_COUNT: AtomicUsize = AtomicUsize::new(0);

fn do_a_call() {
    CALL_COUNT.fetch_add(1, Ordering::SeqCst);
}


// cargo run --bin atomics
fn main() {
    do_a_call();
    do_a_call();
    do_a_call();

    println!("called {}", CALL_COUNT.load(Ordering::SeqCst));
}