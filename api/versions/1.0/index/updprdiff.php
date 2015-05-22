<?php
defined('DS_ENGINE') or die('web_demon laughs');

$load = new bcdata();

$res = core::$db->query('SELECT * FROM `ds_maindata` ORDER BY `id`;');
while($data = $res->fetch_assoc())
{
  $addsql = '';
  $price_dif = $load->pricedif($data['price'], $data['now_price']);
  if($data['market_price'] AND $data['now_price'])
  {
    $profit_rub = $load->profitrub($data['market_price'], $data['now_price']);
    $profit_proc = $load->prifitproc($data['market_price'], $data['now_price']);

    if($profit_proc > 2000)
    {
      $profit_rub = 0;
      $profit_proc = 0;
    }

    $addsql = ' , `profit_rub` = "'.$profit_rub.'", `profit_proc` = "'.$profit_proc.'" ';
  }
  core::$db->query('UPDATE `ds_maindata` SET `price_dif` = "'.$price_dif.'" '.$addsql.' WHERE `id` = "'.core::$db->res($data['id']).'" ;');
}

echo 'ok';