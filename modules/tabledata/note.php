<?php

if(!in_array(core::$rights, [10, 11, 100]))
  exit('access denied');

if(!isset($_GET['action'])) exit;

$id = intval($_POST['id']);
$text = $_POST['text'];

if(strlen($text) > 400) {
    ajax_response(['error' => '1']);
}

if($_GET['action'] == 'save') {
    
    $text = core::$db->res(htmlentities(strip_tags($text), ENT_QUOTES));
    
    $query = core::$db->query('SELECT * FROM `lot_notes` WHERE `lot_id` = "'.$id.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1');
    if ($query->num_rows > 0) {
        $lot = $query->fetch_assoc();
        core::$db->query('UPDATE `lot_notes` SET `text` = "'.$text.'" WHERE `id` = "'.$lot['id'].'"');
    } else {
        core::$db->query('INSERT INTO `lot_notes` (text,lot_id, user_id) VALUES("'.$text.'", "'.$id.'", "'.core::$user_id.'")');
    }
    
} elseif ($_GET['action'] == 'delete') {
    core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "'.$id.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1');
}

ajax_response(['error' => '0']);

