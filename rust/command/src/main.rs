
//Инкапсуляция запроса, вызывающему invoker'у не важно какая команда будет послана так как они все реализуют один интерфейс

//Придает системе гибкость, отделяя инициатора(invoker) запроса от его получателя(receiver).
// invoker вызывающий
// receiver получатель

trait Command {
    fn execute(&self);
}

struct MacroCommand {
    stack: Vec<Box<Command>>,
}
impl MacroCommand {
    fn new() -> MacroCommand {
        MacroCommand {
            stack: Vec::new(),
        }
    }
    fn push(&mut self, cmd: Box<Command>) {
        self.stack.push(cmd);
    }
    fn undo(&mut self) {
        self.stack.pop();
    }
    fn clear(&mut self) {
        self.stack.clear();
    }
}
impl Command for MacroCommand {
    fn execute(&self) {
        for command in &self.stack {
            command.execute();
        }
    }
}


struct DrawCommand {
    drawable: Box<Drawable>,
    x: u32,
    y: u32,
}
impl DrawCommand {
    fn new(drawable: Box<Drawable>, x: u32, y: u32) -> DrawCommand {
        DrawCommand {
            drawable: drawable,
            x: x,
            y: y,
        }
    }
}
impl Command for DrawCommand {
    fn execute(&self) {
        self.drawable.draw(self.x, self.y);
    }
}

trait Drawable {
    fn draw(&self, x: u32, y: u32);
}
#[derive(Clone)]
struct DrawCanvas {}
impl DrawCanvas {
    fn new() -> DrawCanvas {
        DrawCanvas {}
    }
}
impl Drawable for DrawCanvas {
    fn draw(&self, x: u32, y: u32) {
        println!("draw(x:{}, y:{})", x, y);
    }
}

fn main() {
    let mut history = MacroCommand::new();
    let canvas = Box::new(DrawCanvas::new());
    // TODO
    let cmd1 = Box::new(DrawCommand::new(canvas.clone(), 1, 1));
    let cmd2 = Box::new(DrawCommand::new(canvas.clone(), 2, 2));
    history.push(cmd1);
    history.push(cmd2);
    println!("----------");
    history.execute();
    println!();
    println!("---undo---");
    history.undo();
    history.execute();
    println!();
    println!("---clear---");
    history.clear();
    history.execute();
}

/*
----------
draw(x:1, y:1)
draw(x:2, y:2)

---undo---
draw(x:1, y:1)

---clear---

*/