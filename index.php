<?php
namespace index;
define('INDEX_PATH',__DIR__);
require 'Protecteds/Autoload.php';

if(isset($argv[1])){
    $link = explode('/',trim($argv[1],'/'));
    if(count($link) >= 1){
        $getClass = '\App\Commands\\'.$link[0];
        $class = new $getClass();
        if(method_exists($class,$link[1])){
            $class->{$link[1]}($link);
        }
    }
}

?>