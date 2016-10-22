<?php

include(CMS_ROOT.'/pay/yijipay/lib/Base.class.php');
include(CMS_ROOT.'/pay/yijipay/config.php');
spl_autoload_register(function($classname){
    if($classname){
        $file = __DIR__.'/lib/'.$classname.'.class.php';
        if(is_file($file)){
            require_once($file);
        }
    }
},true);