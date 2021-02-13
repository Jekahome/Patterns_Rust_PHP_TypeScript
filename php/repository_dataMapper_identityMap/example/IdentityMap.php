<?php
namespace example;

require "../autoload.php";

use store\IdentityMap;

class A{
    public int $id;
    public string $name;
    public function __construct(int $id,string $name)
    {
        $this->id = $id;
        $this->name= $name;
    }
}

function testMutation(){
    $identity = IdentityMap::getInstance();
    $obj = $identity->getObject(12,A::class);
    $obj->name = "MUT-Q";
}

function main(){
    $identity = IdentityMap::getInstance();

    $obj = new A(12,"Q");
    $identity->attach($obj->id,$obj);

    $obj_2 = $identity->getObject(12,A::class);
    if ($obj !== $obj_2)throw new \Exception("1 - Not identity");

    testMutation();

     if ($obj->name !== $obj_2->name)throw new \Exception("2 - Not identity");
    // if ($obj->name !== "MUT-Q" || $obj_2->name !== "MUT-Q")throw new \Exception("3 - Not identity");
     printf("%s == %s == %s\n",$obj->name,$obj_2->name,$identity->getObject(12,A::class)->name);
}
// Задача обеспечить единственный экземплер в системе обеспечена !

main();
