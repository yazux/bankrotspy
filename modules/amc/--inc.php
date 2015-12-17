<?php

defined('DS_ENGINE') or die('web_demon laughs');

/*
if(!core::$user_id) { 
    
    $message = 'Для просмотра данной страницы вам необходимо <a href="/login">авторизоваться</a> или <a href="/user/register">зарегистрироваться</a> на сайте.';
    
    engine_head(lang('title'));
    temp::assign('title', lang('title'));
    
    
    temp::HTMassign('message', $message);
    temp::display('access.denied');
    engine_fin();
}

if(!CAN('rating_arbitration')) {
    
    $message = 'Рейтинг АУ доступен только на платной подписке VIP-доступ';
    engine_head(lang('title'));
    temp::assign('title', lang('title'));
    temp::HTMassign('message', $message);
    temp::display('access.denied');
    engine_fin();
}*/

$access = true;

if(!core::$user_id) {
    $access = '<i class="fa fa-lock" onmouseout="toolTip()" onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')"></i>';
} elseif (!CAN('rating_arbitration')) {
    $access = '<i class="fa fa-lock" onmouseover="toolTip(\'Информация доступна на подписке VIP\')"></i>';
}