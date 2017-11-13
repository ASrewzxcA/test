<?php
namespace App\Components;
use App\Components\Logger\Abstracts\AbstractWrite;

class Logger extends AbstractWrite
{
    /**
     * @param $messages
     * @param string $level
     * @return bool
     */
    public function write($messages,$level = 'low',$saveData = false)
    {
        $data = parent::prepare($messages,$level);

        try {
            if($saveData){
                if(file_exists($data['path'])){
                    $oldData = file_get_contents($data['path']);
                    try{
                        $oldDataDecode = (array)json_decode($oldData,true);
                        if(is_array($oldDataDecode) && is_array($data['messages'])){
                            $data['messages'] = json_encode(array_merge($oldDataDecode,$data['messages']),JSON_UNESCAPED_SLASHES);
                        }
                    }catch (\Exception $exception){
                        $data['messages'] = json_encode($data['messages'],JSON_UNESCAPED_SLASHES);
                    }
                }
                else{
                    $data['messages'] = json_encode($data['messages'],JSON_UNESCAPED_SLASHES);
                }
            }
            file_put_contents($data['path'], $data['messages']);
            return true;
        }
        catch (\Exception $exception){
            new \Exception($exception->getMessage());
            return false;
        }
    }

    public function read($path){
        $data = file_get_contents($path);
        try{
            return json_decode($data,true);
        }catch (\Exception $exception){
            return $data;
        }
    }
}