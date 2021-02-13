<?php
/**
Lazy Load - загрузка данных по мере необходимости

Вместо этого ставится маркер о том, что данные не загружены и их надо загрузить в случае, если они понадобятся. Как известно, если Вы ленивы, то вы выигрываете в том случае, если дело, которое вы не делали на самом деле и не надо было делать.

Существует четыре основных варианта ленивой загрузки.

Lazy Initialization (Ленивая Инициализация) использует специальный макер (обычно null), чтобы пометить поле, как не загруженное. При каждом обращении к полю проверяется значение маркера и, если значение поля не загружено - оно загружается.
Virtual Proxy (Виртуальный Прокси) - объект с таким же интерфейсом, как и настоящий объект. При первом обращении к методу объекта, виртуальный прокси загружает настоящий объект и перенаправляет выполнение.
Value Holder (Контейнер значения) - объект с методом getValue. Клиент вызывает метод getValue, чтобы получить реальный объект. getValue вызывает загрузку.
Ghost (Призрак) - объект без каких-либо данных. При первом обращении к его методу, призрак загружает все данные сразу.
 */

class OBJ
{
    public $value;
    public function __construct($data){
        $this->value= $data;
    }
    public function show()
    {
        return $this->value."\n";
    }
}


class Data{
    public $data;
    const ONE=1;
    const TWO=2;

    public function __construct(){
        $this->data=['1'=>null,'2'=>null];
    }

    public function get($key){
        if( $this->data[$key] != null) {
            return $this->data[$key];
        }else{
           return $this->data[$key]=$this->load($key);
        }
    }

    private function load($key){
        echo  "LOAD\n";
        if($key==self::ONE  ){
            return new OBJ(1);
        }else if(  $key==self::TWO ){
            return new OBJ(2);
        }
    }
}

$Data=new Data();
echo $Data->get(Data::TWO)->show();
echo $Data->get(Data::TWO)->show();
echo $Data->get(Data::TWO)->show();
echo $Data->get(Data::ONE)->show();