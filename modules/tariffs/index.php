<?php
defined('DS_ENGINE') or die('web_demon laughs');

$ordertime = time();

$res = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');
$rmenu = array();

while ($data = $res->fetch_array()) {
    $loc = array();
    $loc['id'] = $data['id'];
    $loc['name'] =  $data['name'];
    $loc['subtext'] = text::out($data['descr'], 0, $data['id']);
    $loc['longtime'] = $data['longtime'];
    $loc['price'] = $data['price'];
    $loc['order'] = core::$set['market_prefix'].';'.core::$user_id.';'.$data['id'].';'.$ordertime;
    $rmenu[] = $loc;
}

if (core::$user_id) {
    $res = core::$db->query('UPDATE `ds_users` SET `ordertimeid` = "'.$ordertime.'" WHERE `id` = "'.core::$user_id.'";');
}

$res = core::$db->query('SELECT * FROM `ds_reg_page` WHERE `id` = "2";');
$dat = $res->fetch_assoc();
text::add_cache($dat['cache']);
$text =  text::out($dat['text'], 0, $dat['id']);

engine_head(lang('tariffs'));
temp::HTMassign('text', $text);
temp::HTMassign('rmenu',$rmenu);

if (core::$user_id) {
    temp::assign('oos_payment_page', $oos_payment_page);
}

temp::display('tariffs.index');
engine_fin();