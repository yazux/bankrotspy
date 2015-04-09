<?php
defined('DS_ENGINE') or die('web_demon laughs');

$res = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['name'] =  $data['name'];
  text::add_cache($data['cache']);
  $loc['subtext'] = text::out($data['descr'], 0, $data['id']);
  $loc['longtime'] = $data['longtime'];
  $loc['price'] = $data['price'];
  $rmenu[] = $loc;
}

engine_head(lang('base_settings'));
temp::HTMassign('rmenu',$rmenu);
temp::display('control.tset');
engine_fin();