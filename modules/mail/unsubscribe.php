<?php

/*
if (!empty($_GET['id'])) {
    
    $userID = getUserFromHash($_GET['id'], $salt);

    if ($userID > 0) {
        core::$db->query('UPDATE `ds_users` SET `subscribe` = "0" WHERE id = "'.$userID.'"');
        func::notify('Рассылка', 'Вы отписались от рассылки', core::$home);
    } else {
        func::notify('Рассылка', 'Произошла ошибка', core::$home);
    }
}
*/

if (!empty($_POST) && !empty($_GET['id'])) {
    
    $userID = getUserFromHash($_GET['id'], $salt);

    if ($userID > 0) {
        core::$db->query('UPDATE `ds_users` SET `subscribe` = "0" WHERE id = "'.$userID.'"');
        func::notify('Рассылка', 'Вы отписались от рассылки', core::$home);
    } else {
        func::notify('Рассылка', 'Произошла ошибка', core::$home);
    }
} 

$id = $_GET['id'];

engine_head('Отписка от рассылки');
temp::assign('id', $id);
temp::display('mail/unsubscribe');
engine_fin();
