<?php
namespace App\Model;

use App\Components\Logger;

class SimpleHtml
{
    /**
     * @return array;
     */
    public function getCatalogData(){
        $logger = new Logger();
        $logger->setPath('./result/');
        $links = $this->getCatalogUrl();

        $emptyLink = $logger->read('./result/empty.log');
        $links = array_diff($links,$emptyLink);

        $products = [];
        foreach ($links as $link){
            $proruct = $this->getProduct($link);
            if($proruct) {
                $products[] = $proruct;
            }
            else{
                $logger->write(array($link),'empty',true);
            }

            ///Лимит
            if(count($products) >= 5){
                break;
            }
        }

        return $products;
    }

    /**
     * Получаем все ссылки каталогов
     * @return array
     */
    protected function getCatalogUrl(){
        $html = new SimpleHtmlDom();
        $html->load_file('http://www.ozon.ru/context/div_beauty/?rand='.rand(99999,9999999));

        $allLinks = [];
        $catalogUrl = $html->find('a');
        foreach ($catalogUrl as $key => $value){
            $allLinks[] = $value->href;
        }

        $relevantLinks = [];
        foreach ($allLinks as $link){
            if(is_numeric(strpos($link,'/catalog/')) || is_numeric(strpos($link,'/brand/'))){
                $relevantLinks[] = $link;
            }
        }
        $html->clear();
        unset($html);
        return $relevantLinks;
    }

    /**
     * Получаем все продукты данной категории
     * @var $link string Ссылка на каталог
     * @return array | false
     * */
    protected function getProduct($link){
        $link = 'http://www.ozon.ru'.$link;
        $html = new SimpleHtmlDom();
        $html->load_file($link);

        if ($html->find('h1.bMainHeader_Header', 0) != null) {
            $brandName = trim($html->find('h1.bMainHeader_Header', 0)->text(), ' ');
        } else {
            return false;
        }

        $products = $html->find('div[itemprop=itemListElement]');

        $items = [];

        foreach($products as $product){
            try {
                if($product->find('div.eOneTile_itemType', 0)) {
                    $type = trim($product->find('div.eOneTile_itemType', 0)->text(), ' ');
                    $items[$brandName][$type][] = array(
                        'img' => trim($product->find('img', 0)->src, '//'),
                        'description' => trim($product->find('div[itemprop=name]', 0)->text(), ' '),
                        'type' => $type,
                        'price' => !$product->find('span.eOzonPrice_main', 0) ? '' : trim($product->find('span.eOzonPrice_main', 0)->text(), ' ')
                    );
                }
            }catch (\Exception $exception){
                $exception->getMessage();
            }
        }

        $html->clear();
        unset($html);
        return $items;
    }

}