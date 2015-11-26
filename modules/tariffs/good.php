<?php
defined('DS_ENGINE') or die('web_demon laughs');

$order_id = $_GET['orderId'];

//проверка на корректсноть строки заказа
if(!preg_match('#^(BSPY)-(\d{0,3})-(\d{0,1})-(\d{0,10})$#', $order_id, $order)) {
   echo denied();
}

//проверяем заказ

$query = core::$db->query('SELECT * FROM `ds_paid` WHERE `paidid` = "'.$order_id.'"');

if($query->num_rows > 0 ) {
    echo denied();
}

$market_prefix = intval($order[1]);
$user_id = intval($order[2]);
$tariff_id = intval($order[3]);
$order_time =intval($order[4]);

if (core::$user_id === $user_id) {

    $check = merchant_api("checkPayment", array("marketPlace" => $market_place_id, "orderId" => $order_id));

    if ($check['status'] === 'STATUS_SUCCESS') {
        
        engine_head(lang('tariffs'));
        temp::display('tariffs.waitforpay');
        engine_fin();
    
    } else {

        if (core::$user_id) {
            new mail_temp('./data/engine/');
            mail_temp::assign('home', core::$home);
            mail_temp::assign('orderid', $order_id);
            mail_temp::assign('userid', $user_id);
            mail_temp::assign('details', @$check['errorCode'].' / '.@$check['errorDescription'].' / '.$check['payment']['state']);
            $mail_body = mail_temp::get('mail_buy_err');
            mail::send('imbagroup@yandex.ru', lang('mail_head_err').' '.$order_id.' ('.core::$set['site_name_main'].')', $mail_body);
        }

        engine_head(lang('tariffs'));
        temp::assign('orderid', @$order_id);
        temp::assign('error_code', @$check['errorCode'].' / '.@$check['payment']['state']);
        temp::assign('error_description', @$check['errorDescription']);
        temp::display('tariffs.bad');
        engine_fin();
    }
} else {
    echo denied();
}