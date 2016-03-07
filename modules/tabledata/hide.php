<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!in_array(core::$rights, [10, 11, 100])) {
    exit('access denied');
}

$item = abs(intval(POST('itemid')));
$action = abs(intval(POST('actionid')));

if( !$item ) {
    exit('Не переданны данные');
}

$res_art = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "' . $item . '" LIMIT 1;');
if( !$res_art->num_rows ) {
    exit('Такого лота не существует.');
}

$res = core::$db->query('SELECT * FROM `ds_maindata_hide` WHERE `item` = "' . $item . '" AND `user_id` = "' . core::$user_id . '" LIMIT 1;');
if($res->num_rows)
    $item_added = true;
else
    $item_added = false;

if( !$action ) {
    //добавляем в скрытые
    if($item_added)
        exit('Лот добавлен в скрытые.');
  
    core::$db->query('INSERT INTO `ds_maindata_hide` SET `item` = "' . $item . '", `user_id` = "' . core::$user_id . '", `hidetime` = "'.time().'";');
} else {
    //Удаляем из скрытого
    if(!$item_added)
        exit('Лот удален из скрытых.');
    
    core::$db->query('DELETE FROM `ds_maindata_hide` WHERE `item` = "' . $item . '" AND `user_id` = "' . core::$user_id . '" LIMIT 1;');
    //core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "' . $item . '" AND `user_id` = "' . core::$user_id . '" LIMIT 1;');
}

echo 'ok';