<?php
namespace App\Commands;
use  App\Components\Logger;
class Parse
{
    public function start(){
        $simpleHtml = new \App\Model\SimpleHtml();
        $catalogData = $simpleHtml->getCatalogData();

        $loger = new Logger();
        $loger->setPath('./result/');
        $loger->write(json_encode($catalogData),'catalog_'.time());
    }

}