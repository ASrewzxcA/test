<?php
/**
 * Created by PhpStorm.
 * User: harasov
 * Date: 11.11.17
 * Time: 19:27
 */

namespace App\Model;
use  App\Components\Logger;
class GetSite
{
    /**
     * Получаем html страницу
     * @param $args array
     * @return mixed
     */
    public function content($args)
    {
        $proxy = $this->getProxyServer();
        $curl = curl_init($args[0]);
        curl_setopt_array($curl, array(
            CURLOPT_URL => $args[0],
            CURLOPT_CONNECTTIMEOUT => 360,
            CURLOPT_TIMEOUT => 180,
            CURLOPT_PROXY => $proxy,
            CURLOPT_PROXYTYPE => CURLPROXY_HTTP,//CURLPROXY_SOCKS5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_POSTFIELDS => [],
            CURLOPT_HTTPHEADER => array(
                'User-Agent:Mozilla/'.rand(1,5).'.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/'.rand(32,60).'.0.3163.100 Safari/'.rand(400,537).'.36',
                'Accept-Language:ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4',
                'Accept:text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
                "Cache-Control: no-cache",
                "Pragma: no-cache",
            )
        ));

        $result = curl_exec($curl);
        $curlInfo = curl_getinfo($curl);
        $curlError = curl_error($curl);

        $allResult = array(
            'result' => $result,
            'curlInfo' => $curlInfo,
            'curlError' => $curlError,
        );

        $loger = new Logger();
        $loger->setPath('/var/logs/');
        $loger->write($result,'curl_'.time());

        if(isset($result) AND strlen($curlError) == 0){
            return $result;
        }
        else{
            (new \Exception('Ошибка чтения сайта'));
        }
    }

    /**
     * Получим рандомную проксю
     * @return array mixed
     */
    protected function getProxyServer(){
        $proxy = array(
            '185.64.18.25:8080',
            '109.236.111.45:53281',
            '78.108.65.22:8081',
            '94.228.196.23:8081'
        );

        return $proxy[rand(0,(count($proxy) - 1))];
    }
}