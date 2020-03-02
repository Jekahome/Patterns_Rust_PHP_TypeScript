<?php

interface Cook {
   function cook():bool ;
}

class Milk implements Cook{
    public int $val;
    function __construct(int $val)
    {
        $this->val=$val;
    }

    function cook():bool{
        echo ("Cook milk, 5 minutes passed\n");
        return true;
    }
}

class Groats implements Cook{
    public int $weight;
    function __construct(int $weight)
    {
        $this->weight=$weight;
    }

    function cook():bool{
        echo ("Cook Groats, 5 minutes passed\n");
        return true;
    }
}
//Манная каша
class Semolina{
    public Array $ingredients=[];
    public int $val;
    function __construct()
    {
    }
    function add(Cook $ingredient){
         array_push($this->ingredients,$ingredient);
    }
    function cook(){
        $milk = new Milk(1);
        $milk->cook();
        $this->add($milk);

        $groats = new Groats(0.1);
        $groats->cook();
        $this->add($groats);
        echo("Semolina is ready\n");
    }
}

(new Semolina(5))->cook();

