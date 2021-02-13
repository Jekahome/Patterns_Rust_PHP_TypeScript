<?php
spl_autoload_register(function ($class_name) {
    // echo "load class $class_name.\n";
    list($folder,$class) =  explode("\\",$class_name);
    include $folder."/".$class . ".php";
});
