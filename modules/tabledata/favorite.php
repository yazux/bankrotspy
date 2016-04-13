<?php

// перевести ответы на json
defined('DS_ENGINE') or die('web_demon laughs');

if (!CAN('add_favorites')) {
    exit('access denied');
}

$item = abs(intval(POST('itemid')));
$action = abs(intval(POST('actionid')));

if(!$item)
    ajax_response([
        'error'     => true,
        'message'   => 'Не передан ID лота.'
    ]);

// Проеряем есть ли такой лот в природе?
$res_art = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "'.$item.'" LIMIT 1;');
if( !$res_art->num_rows ) {
    ajax_response([
        'error'     => true,
        'message'   => 'Такого лота не существует.'
    ]);
}

// Проверяем есть ли этот лот уже в избранном?
$res = core::$db->query('SELECT * FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
// Если этот лот уже был добавлен в избранное
if($res->num_rows)
    $item_added = true;
else
    $item_added = false;

if(!$action) {
    //добавляем в избранное
    if($item_added)
        ajax_response([
            'error'     => false,
            'message'   => 'Этот лот уже был добавлен в избранное реньше.'
        ]);
    core::$db->query('INSERT INTO `ds_maindata_favorive` SET `item` = "'.$item.'", `user_id` = "'.core::$user_id.'", `favtime` = "'.time().'";');
    
    ajax_response([
        'error'     => false,
        'message'   => 'Лот успешно добавлен в избранное.'
    ]);
} else {
    //Удаляем из избранного
    if( !$item_added )
        ajax_response([
            'error'     => false,
            'message'   => 'Этот лот уже был удален из избранного ренее.'
        ]);
    
    core::$db->query('DELETE FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
    core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
    
    ajax_response([
        'error'     => false,
        'message'   => 'Лот успешно удален из избранного.'
    ]);
}