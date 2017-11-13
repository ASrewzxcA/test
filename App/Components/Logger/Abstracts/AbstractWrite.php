<?php
namespace App\Components\Logger\Abstracts;

class AbstractWrite implements \App\Components\Logger\Interfaces\WriteInterface
{
    protected $path;

    CONST EXTENSION = '.log';
    CONST SYSTEM_LOG = 'system';
    CONST EXCEPTION_LOG= 'exception';
    CONST LOW_LOG = 'exception';

    final public function setPath($path){
        $this->path = $path;
    }

    final public function prepare($messages,$level){
        switch ($level){
            case AbstractWrite::SYSTEM_LOG:
                $logPath = $this->path.AbstractWrite::SYSTEM_LOG.AbstractWrite::EXTENSION;
            break;
            case AbstractWrite::EXCEPTION_LOG:
                $logPath = $this->path.AbstractWrite::EXCEPTION_LOG.AbstractWrite::EXTENSION;
            break;
            case AbstractWrite::LOW_LOG:
                $logPath = $this->path.AbstractWrite::LOW_LOG.AbstractWrite::EXTENSION;
            break;
            case $level:
                $logPath = $this->path.$level.AbstractWrite::EXTENSION;
            break;
        }

        try{
            $msg = $messages;//json_encode($messages);
        }catch (\Exception $e){
            $msg = $messages;
        }

        return array(
            'path' => $logPath,
            'messages' => $msg
        );
    }
}