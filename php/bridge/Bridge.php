<?php
// Проблема:
// Корень проблемы заключается в том, что мы пытаемся расширить классы
// фигур сразу в двух независимых плоскостях  — по виду и по материалу.
// Именно это приводит к разрастанию дерева классов.
// При создании нового материала придется создавать все его варианты типов блока
abstract class BodyFigure{
    abstract function area();
    abstract function weight();
}
// класс одновременно реализует поведение квадрата и железа
class SquareIron extends BodyFigure{
   private $part;
   private const gravity_metal=7.874;// 1/см
   public function area(){
       return pow($this->part,2);
   }
   public function weight(){
       $this->area()*$this->gravity_metal;
   }
}
// класс одновременно реализует поведение кругв и железа
class CircleIron extends BodyFigure{
   private $radius;
   private const gravity_metal=7.874;// 1/см
   public function area(){
        return  M_PI*pow($this->radius, 2);
   }
   public function weight(){
        $this->area()*$this->gravity_water;
   }
}
// класс одновременно реализует поведение квадрата и воды
class SquareWater extends BodyFigure{
   private $part;
   private const gravity_water=998.5;// 1л
   public function area(){
       return pow($this->part,2);
   }
   public function weight(){
       $this->area()*$this->gravity_water;
   }
}
// класс одновременно реализует поведение круга и воды
class CircleWater extends BodyFigure{
    private $radius;
    private const gravity_water=998.5;// 1л
    public function  area(){
        return  M_PI*pow($this->radius, 2);
    }
   public function weight(){
       $this->area()*$this->gravity_water;
   }
}

// Решение:
// Паттерн Мост предлагает заменить наследование агрегацией или композицией.
// Для этого нужно выделить одну из таких «плоскостей» в отдельную иерархию и
// ссылаться на объект этой иерархии, вместо хранения его состояния и поведения внутри одного класса.
// При создании нового материала или типа блока не придется создавать этот вариант для всех
interface Material{
    public function weight();
}
class Iron implements Material{
    private const gravity_metal=7.874;// 1/см
    public function weight(){
        $this->area()*$this->gravity_water;
   }
}
class Water implements Material{
    private const gravity_water=998.5;// 1л
    public function weight(){
       $this->area()*$this->gravity_water;
   }
}

class Square extends BodyFigure{
   private $part;
   private $material;
   public function __construct(Material $material){
       $this->material=$material;
   }
   public function area(){
       return pow($this->part,2);
   }
   public function weight(){
       return $this->material->weight();
   }
}

class Circle extends BodyFigure{
    private $radius;
    private $material;
    public function __construct(Material $material){
       $this->material=$material;
    }
    public function  area(){
        return  M_PI*pow($this->radius, 2);
    }
    public function weight(){
       return $this->material->weight();
   }
}
