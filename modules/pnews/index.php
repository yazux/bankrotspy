<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_platform_news`')->count();

$outnews = array();
$res = core::$db->query('SELECT * FROM `ds_platform_news` LIMIT '.nav::$start.', '.nav::$kmess.';');
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['time'] = ds_time($data['time'], '%H:%M');
  $loc['data'] = ds_time($data['time'], '%d %B2 %Y');
  $loc['text'] = text::st($data['text']);
  $outnews[] = $loc;
}

engine_head(lang('pnews'));
temp::HTMassign('total', $total);
temp::HTMassign('outnews', $outnews);
temp::HTMassign('navigation', nav::display($total, core::$home.'/pnews?'));
temp::display('pnews.index');
engine_fin();