<?php

// перевести ответы на json
defined('DS_ENGINE') or die('web_demon laughs');

//if (!CAN('add_favorites')) {
//    exit('access denied');
//}

$idsArray = array();
$ids = GET('ids');
if ( $ids ) {
    $idsArray = explode( ',', $ids );
}
$action = GET('action');

if( !$idsArray )
    exit('nothing to do with');

foreach( $idsArray as $item ) {
    $res_art = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "'.$item.'" LIMIT 1;');
//    if(!$res_art->num_rows)
//        exit('item no exists');

    $res = core::$db->query('SELECT * FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
    if( $res->num_rows )
        $item_added = true;
    else
        $item_added = false;

    if( $action == 'add' ) {
        //добавляем в избранное
        if( !$item_added )
            core::$db->query('INSERT INTO `ds_maindata_favorive` SET `item` = "'.$item.'", `user_id` = "'.core::$user_id.'", `favtime` = "'.time().'";');
    } else {
        //Удаляем из избранного
        if( $item_added ) {
            core::$db->query('DELETE FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
            core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
        }
    }
}
if( $action == 'add' ) {
    $message = 'Лоты добавлены в избранное';
} else {
    $message = 'Лоты удалены из избранного';
}

ajax_response([
    'error'     => '0',
    'message'   => $message
]);