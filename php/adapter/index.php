<?php

declare(strict_types=1);
// Адаптер  позволяет объектам с несовместимыми интерфейсами работать вместе.
//Это объект-переводчик, который трансформирует интерфейс или данные одного объекта в такой вид, чтобы он стал понятен другому объекту.

class Banner {
    private string $text;
    function __construct(string $text){$this->text=$text;}
    function show_with_paren(){
         echo("(".$this->text.")\n");
    }
    function show_with_aster(){
         echo("*".$this->text."*\n");
    }
}

class PrintBanner {
    private Banner $banner;
    function __construct(string $text){
        $this->banner=new Banner($text);
    }
    function print_weak(){
         $this->banner->show_with_paren();
    }
    function print_strong(){
         $this->banner->show_with_aster();
    }
}

$p = new PrintBanner("Hello");
$p->print_weak();
$p->print_strong();

/*
(Hello)
*Hello*

*/