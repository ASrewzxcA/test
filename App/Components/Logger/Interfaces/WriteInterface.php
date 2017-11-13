<?php
/**
 * Created by PhpStorm.
 * User: harasov
 * Date: 09.11.17
 * Time: 19:06
 */

namespace App\Components\Logger\Interfaces;


interface WriteInterface
{
    public function prepare($messages,$level);
    public function setPath($path);
}