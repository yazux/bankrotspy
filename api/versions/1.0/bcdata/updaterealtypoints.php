<?php


defined('DS_ENGINE') or die('web_demon laughs');

$data = $_POST['itemdata'];
//var_dump($_POST);
if(!$data)
  exit('No item data');

if(!is_array($data)) {
    exit('Wrong data array');
} else {
    $result = array();
    
    foreach($data as $id => $item) {
        $item = iconv("windows-1251", "UTF-8", $item);
        $item = explode(';', $item);
    
        $result[$id]['id'] = intval($item[0]); 
        $result[$id]['price'] = intval($item[1]);
        $result[$id]['hint'] = htmlentities(trim($item[2]));
        $result[$id]['pricem2'] = $item[3];
        $result[$id]['link'] = base64_decode($item[4]);
        $result[$id]['prices'] = explode('#', $item[5]);
    }
}
//var_dump($result);
foreach ($result as $i => $item) {

    $query = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "'.core::$db->res($item['id']).'" ;');
    if(!$query->num_rows) {
        echo 'Wrong id: ' . $item['id']. PHP_EOL;
        continue;
    }
        
    core::$db->query('UPDATE `ds_maindata` SET `market_price` = "'.$item['price'].'" WHERE `id` = "'.core::$db->res($item['id']).'";');
    core::$db->query('REPLACE INTO `ds_maindata_hint` SET `id` = "' . core::$db->res($item['id']) . '", `price` = "' . core::$db->res($item['pricem2']) . '", `link` = "' . core::$db->res($item['link']) . '", `text` = "' . core::$db->res($item['hint']) . '";');
    
    // удаляем данные для графика
    core::$db->query('DELETE FROM `lot_prices` WHERE `id` = "' . core::$db->res($item['id']) . '"');
    // вставляем данные для графика
    if(!empty($item['prices'])) {
        foreach($item['prices'] as $price) {
            core::$db->query('INSERT INTO `lot_prices` (id, price) VALUES ("' . core::$db->res($item['id']) . '", "' . core::$db->res($price) . '")');
        }
    }
}

echo 'ok' . PHP_EOL;