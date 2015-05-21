<?php
defined('DS_ENGINE') or die('web_demon laughs');

$load = new bcdata();

$res = core::$db->query('SELECT * FROM `ds_maindata` ORDER BY `id`;');
while($data = $res->fetch_assoc())
{
  $price_dif = $load->pricedif($data['price'], $data['now_price']);
  core::$db->query('UPDATE `ds_maindata` SET `price_dif` = "'.$price_dif.'" WHERE `id` = "'.core::$db->res($data['id']).'" ;');
}

echo 'ok';