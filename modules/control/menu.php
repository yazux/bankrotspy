<?php
defined('DS_ENGINE') or die('web_demon laughs');

$res = core::$db->query('SELECT * FROM `ds_menu` ORDER BY `sort`;');
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['name'] = $data['name'];
  $loc['link'] = str_replace('<<home_link>>', core::$home, $data['link']);
  $rmenu[] = $loc;
}

engine_head(lang('base_settings'));
temp::HTMassign('rmenu',$rmenu);
temp::display('control.menu');
engine_fin();