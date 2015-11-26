<?php

defined('DS_ENGINE') or die('web_demon laughs');

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
}