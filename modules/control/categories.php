<?php
defined('DS_ENGINE') or die('web_demon laughs');

$res = core::$db->query('SELECT *, `ds_maindata_category`.`name` FROM `ds_main_cat_spec` LEFT JOIN `ds_maindata_category` ON `ds_main_cat_spec`.`id` = `ds_maindata_category`.`id` ORDER BY `ds_main_cat_spec`.`id` ASC;');
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['name'] = $data['name'];
  $keywords = explode("\n", str_replace("\r\n", "\n", $data['slova']));
  $loc['undtext'] =implode(', ', $keywords);
  $rmenu[] = $loc;  
}

engine_head(lang('admin_control'));
temp::HTMassign('rmenu',$rmenu);  
temp::display('control.categories');
engine_fin();