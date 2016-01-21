<?php

defined('DS_ENGINE') or die('web_demon laughs');

$result = core::$db->query('SELECT id, grafik1, price FROM `ds_maindata` WHERE (platform_id   IN (22,23,24,25,27,29,30,31)) AND LENGTH(grafik1) > 10');

$dom = new simple_html_dom();

while($data = $result->fetch_array()) {
    $table = $dom->load($data['grafik1']);
    /*
    echo '<table>';
    
    echo $data['grafik1'];
    echo '</table>';*/
    
    $table = $table->find('tr');
    
    if (!empty($table)) {
        array_shift($table);
        foreach ($table as $i =>$price) {
            
                        
            $startDate = strtotime($price->children(0)->innertext);
            $endDate = strtotime($price->children(1)->innertext);
            
            if($startDate < time() && $endDate > time()) {
                //var_dump($price->children(0)->innertext . ' - ' . $price->children(1)->innertext . ' = ' . $price->children(2)->innertext);
                $price_now = substr(str_replace ('&nbsp;', '', $price->children(2)->innertext), 0 , -3);
                $price_now = intval($price_now);
                
                echo 'update id: ' . $data['id'] . PHP_EOL;
                echo 'date: ' . $price->children(0)->innertext . ' - ' . $price->children(1)->innertext . ')' . PHP_EOL;
                echo 'price: ' .  $price_now . PHP_EOL . PHP_EOL;
                break;
            }
        }
        
        if ($price > 0 && $price !== $data['price']) {
            core::$db->query('UPDATE `ds_maindata` SET now_price = "'.$price_now.'" WHERE `id` = "'.$data['id'].'";');
        }
    }
}
