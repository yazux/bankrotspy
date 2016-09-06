<?php

// надо подумать над кодами и сообщениями ошибок

if (!CAN('lot_note')) {
    ajax_response([
        'error'     => '1',
        'message'   => 'Данная функция доступна на тарифном плане VIP'
    ]);
}

if(!isset($_GET['action'])) exit;

$id = intval($_POST['id']);
$text = $_POST['text'];


if (strlen($text) > 400) {
    ajax_response([
        'error'     => '1',
        'message'   => 'Максимальное колличество символов 400'
    ]);
}

if($_GET['action'] == 'save') {
    
    $text = core::$db->res(htmlentities(strip_tags($text), ENT_QUOTES));
    
    $query = core::$db->query('SELECT * FROM `lot_notes` WHERE `lot_id` = "'.$id.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1');
    if ($query->num_rows > 0) {
        $lot = $query->fetch_assoc();
        core::$db->query('UPDATE `lot_notes` SET `text` = "'.$text.'" WHERE `id` = "'.$lot['id'].'"');
        
        $message = 'Комментарий обновлен';
        
    } else {
        core::$db->query('INSERT INTO `lot_notes` (text,lot_id, user_id) VALUES("'.$text.'", "'.$id.'", "'.core::$user_id.'")');
        $message = 'Комментарий добавлен';
    }
    
} elseif ($_GET['action'] == 'delete') {
    core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "'.$id.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1');
    $message = 'Комментарий удален';
}

ajax_response([
    'error'     => '0',
    'message'   => $message
]);

