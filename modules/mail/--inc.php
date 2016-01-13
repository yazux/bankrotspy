<?php

//для хеша подписки
$salt = '(^_^)(^_^)(^_^)';

function getUserFromHash($hash, $salt)
{
    $query = core::$db->query('SELECT count(id) FROM `ds_users`');
    $totalUser = $query->count();
   
    for ($i = 1; $i < $totalUser; $i++) {
        if ($hash === md5($i.$salt)) {
            return $i;
        }
    }
    return 0;
}

function createHash($userID, $salt)
{
    return md5($userID.$salt);
}
