<?php
defined('DS_ENGINE') or die('web_demon laughs');

$ordertime = time();

$res = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');
$rmenu = array();

while ($data = $res->fetch_array()) {
    $loc = array();
    $loc['id'] = $data['id'];

    $message_one = array(
        "amount" => $data['price'],
        "details" => $data['name'],
        "customerRating" => "5",
        "customerAccount" => core::$user_id,
        "orderId" => core::$set['market_prefix'].'-'.core::$user_id.'-'.$data['id'].'-'.$ordertime,
        "successUrl" => core::$home.'/tariffs/good',
        "failUrl" => core::$home.'/tariffs/bad',
        "paymentMethod" => "",
        "customerPhone" => "",
        "customerEmail" => core::$user_mail,
        "customerComment" => "",
        "data" => array(
            "user" => core::$user_id,
            "debug" => "1"
        )
    );
    
    $messageText_one = json_encode($message_one);
    $http_params_one = array(
        "marketPlace" => $market_place_id,
        "message" => base64_encode($messageText_one),
        "signature" => hash('sha256', $messageText_one . $merchant_key)
    );

    $loc['name'] =  $data['name'];
    if (core::$user_id AND !CAN('paycontent', 0)) {
        $loc['params'] = $http_params_one;
    } else {
        $loc['params'] = array();
    }
    
    text::add_cache($data['cache']);
    $loc['subtext'] = text::out($data['descr'], 0, $data['id']);
    $loc['longtime'] = $data['longtime'];
    $loc['price'] = $data['price'];
    $rmenu[] = $loc;
}

if (core::$user_id AND !CAN('paycontent', 0)) {
    $res = core::$db->query('UPDATE `ds_users` SET `ordertimeid` = "'.$ordertime.'" WHERE `id` = "'.core::$user_id.'";');
}

$res = core::$db->query('SELECT * FROM `ds_reg_page` WHERE `id` = "2";');
$dat = $res->fetch_assoc();
text::add_cache($dat['cache']);
$text =  text::out($dat['text'], 0, $dat['id']);

engine_head(lang('tariffs'));
temp::HTMassign('text', $text);
temp::HTMassign('rmenu',$rmenu);

if (core::$user_id AND !CAN('paycontent', 0)) {
    temp::assign('oos_payment_page', $oos_payment_page);
}

temp::display('tariffs.index');
engine_fin();