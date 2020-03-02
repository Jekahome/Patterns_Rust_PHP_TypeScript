<?php
declare(strict_types=1);

//Инкапсуляция запроса, вызывающему invoker'у не важно какая команда будет послана так как они все реализуют один интерфейс

//Придает системе гибкость, отделяя инициатора(invoker) запроса от его получателя(receiver).
// invoker вызывающий
// receiver получатель
interface Receiver {
    function left();
    function right();
    function undo();
}
class StepReceiver implements Receiver{
    private string $position="";
    private string $prev_position="";
    public function __construct(){}
    public function left(){
        $this->save_pos();
        $this->position = "left";
        echo $this->position,"\n";
    }
    public function right(){
        $this->save_pos();
        $this->position = "right";
        echo $this->position,"\n";
    }
    public function undo(){
       $this->position =  $this->prev_position;
    }
    private function save_pos(){
        $this->prev_position =  $this->position;
    }
}

interface CommandI{
    function execute():bool ;
    function undo();
}

class LeftOnCommand implements CommandI{
    protected Receiver $receiver;

    public function __construct(Receiver $receiver){
        $this->receiver = $receiver;
    }

    function execute(): bool
    {
        $this->receiver->left();
        return true;
    }
    function undo()
    {
        $this->receiver->undo();
    }
}

class RightOnCommand implements CommandI{
    protected Receiver $receiver;

    public function __construct(Receiver $receiver){
        $this->receiver = $receiver;
    }

    function execute(): bool
    {
        $this->receiver->right();
        return true;
    }
    function undo()
    {
        $this->receiver->undo();
    }
}


class CommandHistory{
    private Array $history=[];
    function __construct(){}
    function push(CommandI $command){
        array_push($this->history,$command);

    }
    function pop():CommandI{
        return array_pop($this->history);
    }
    function show(){
        foreach ($this->history as $item){
            $item->execute();
        }
    }
}

// invoker вызывающий
class Invoker{
    private CommandHistory $history;
    function __construct(CommandHistory $history)
    {
        $this->history=$history;
    }
    // Запускаем команду и проверяем, надо ли добавить её в
    // историю.
    function executeCommand(CommandI $command){
        if($command->execute()){
            $this->history->push($command);
        }
    }
    // Берём последнюю команду из истории и заставляем её все
    // отменить. Мы не знаем конкретный тип команды, но это и не
    // важно, так как каждая команда знает, как отменить своё
    // действие.
    function undo(){
       $ommand = $this->history->pop();
        if ($ommand != null){
            $ommand->undo();
        }
    }

    function show(){
        $this->history->show();
    }
}



$invoker = new Invoker(new CommandHistory());

$invoker->executeCommand(new RightOnCommand(new StepReceiver()));
$invoker->executeCommand(new LeftOnCommand(new StepReceiver()));
$invoker->executeCommand(new LeftOnCommand(new StepReceiver()));
$invoker->executeCommand(new LeftOnCommand(new StepReceiver()));

echo "-------------------\n";
$invoker->undo();
$invoker->undo();
$invoker->show();

















