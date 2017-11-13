<?php
class Autoload{

    public static function loadClass($name){

        $explode = explode('\\',$name);
        switch ($explode[1]){
            case 'Components':
                self::required(self::preparePath($explode,'Components'));
            break;
            case 'Commands':
                self::required(self::preparePath($explode,'Commands'));
            break;
            case 'Model':
                self::required(self::preparePath($explode,'Model'));
            break;
        }
    }

    public static function required($requiredFileName){
        if(file_exists($requiredFileName)){
            require $requiredFileName;
        }
        else{
            new Exception('Фаил не найден');
        }
    }

    protected static function preparePath($explode,$control){
        if($control == 'Components'){
            if(count($explode) <= 3){
                $explode[] = $explode[2];
            }
        }
        $path = trim(implode('/',$explode),'/').'.php';
        return $path;
    }
}

spl_autoload_register(array('Autoload', 'loadClass'));