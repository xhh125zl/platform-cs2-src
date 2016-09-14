<?php
ini_set("display","On");
require_once(__DIR__.'/lib/teegon.php');

spl_autoload_register(function($classname){
    if($classname){
        $file = __DIR__.'/lib/'.$classname.'.class.php';
        if(is_file($file)){
            require_once($file);
        }
    }
},true);