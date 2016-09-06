<?php
defined('DS_ENGINE') or die('web_demon laughs');

// Проверка на доступ к скрипту только админа
if( core::$rights < 100 ) {
    exit('Отказано в доступе');
}

// Если админ имеет право редактировать пользователя
if( CAN('edit_user', $data['rights']) ) {

    // Получаем ID пользователя и новых прав
    $userId = abs((int)GET('user_id'));
    $rightId = (int)GET('right_id');

    // Если установлен идешник пользователя
    if( $userId > 0 ) {
        
        // Выставляем пользователю права
        if ( $rightId == -1 ) {
            // Выключаем пользователя выставляя несуществующие права и нулевую активность
            core::$db->query('UPDATE `ds_users` SET `rights` = -1, `active` = 0 WHERE `id` = "'.core::$db->res($userId).'" ');
            exit('Пользователь ID ' . $userId . ' заблокирован!');
        } else {
            // Получаем список ролей в системе
            $eng_right = user::get_rights();

            // Если пытаемся выставить неизвестную роль
            if( !isset($eng_right[$rightId]) ) {
                exit('Попытка выставить несуществующую роль.');
            }

            // Запрос к базе на выставление необходимых прав
            core::$db->query('UPDATE `ds_users` SET `rights` = "'.core::$db->res($rightId).'", active = 1 WHERE `id` = "'.core::$db->res($userId).'" '); 

            exit('Права были выставлены!');
        } 
    } else {
        exit('Не переданы ID пользователя.');
    }
} else {
    exit('Отказано в доступе.');
}


