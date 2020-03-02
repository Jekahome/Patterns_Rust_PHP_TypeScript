

//Инкапсуляция запроса, вызывающему invoker'у не важно какая команда будет послана так как они все реализуют один интерфейс

//Придает системе гибкость, отделяя инициатора(invoker) запроса от его получателя(receiver).
// invoker вызывающий
// receiver получатель
interface Receiver {
    left();
    right();
    undo();
}

class StepReceiver implements Receiver{
    private position:string;
    private prev_position:string;

    constructor(){}
    left(){
        this.save_pos();
        this.position="left";
        console.log(this.position,"\n");
    }
    right(){
        this.save_pos();
        this.position="right";
        console.log(this.position,"\n");
    }
    undo(){
        this.position=this.prev_position;
    }

    private save_pos(){
        this.prev_position=this.position;
    }

}

interface CommandI {
    execute():boolean;
    undo();
}

// Команды
class LeftOnCommand implements CommandI {
    protected receiver:Receiver;
    public  constructor( receiver:Receiver) {
      this.receiver = receiver;
    }
    public  execute():boolean {
        this.receiver.left();
        return true;
    }
    public undo(){
        this.receiver.undo();
    }
}
class RightOnCommand implements CommandI {
    protected receiver:Receiver;
    public  constructor( receiver:Receiver) {
        this.receiver = receiver;
    }
    public  execute():boolean {
        this.receiver.right();
        return true;
    }
    public undo(){
        this.receiver.undo();
    }
}

// Глобальная история команд — это стек.--------------------------------------------------------------------------------
class CommandHistory {
    private history:Array<CommandI>;
    constructor(){
        this.history = [];
    }
    // добавление в конец
    push(c:CommandI){
        this.history.push(c);
    }
    // выходит первым с конца
    pop():CommandI{
        return this.history.pop();
    }
    // для примера отображения состояния получателя
    show(){
        for (let cmd of this.history) {
            cmd.execute();
        }
    }
}


// invoker вызывающий
class Invoker {
    history: CommandHistory;
    constructor(history: CommandHistory){this.history=history;}
    // Запускаем команду и проверяем, надо ли добавить её в
    // историю.
    executeCommand(command:CommandI){
        if(command.execute()){
            this.history.push(command);
        }
    }

    // Берём последнюю команду из истории и заставляем её все
    // отменить. Мы не знаем конкретный тип команды, но это и не
    // важно, так как каждая команда знает, как отменить своё
    // действие.
    undo(){
        let command = this.history.pop();
        if (command != null){
            command.undo();
        }
    }

    show(){
        this.history.show();
    }
}


 new StepReceiver();

let invoker:Invoker = new Invoker(new CommandHistory());
invoker.executeCommand(new RightOnCommand(new StepReceiver()));
invoker.executeCommand(new LeftOnCommand(new StepReceiver()));
invoker.executeCommand(new LeftOnCommand(new StepReceiver()));
invoker.executeCommand(new LeftOnCommand(new StepReceiver()));
console.log("-------------");
invoker.undo();
invoker.undo();
invoker.show();

/*
right
 left
 left
 left
 -------------
 right
 left
*/



