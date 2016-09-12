<?php
//实现本目录下所有类的自动加载机制
function __autoload($classname){
    $classpath=$_SERVER["DOCUMENT_ROOT"] . "./include/library/CompanyB2c/".$classname.'.class.php';
    if(file_exists($classpath)){
        require_once($classpath);
    }
    else{
        echo 'class file'.$classpath.'not found!';
    }
}