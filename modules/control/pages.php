<?php
defined('DS_ENGINE') or die('web_demon laughs');

$arr3 = array();
$out3 = array();
$res = core::$db->query('SELECT * FROM `ds_pages` ORDER by `id`;');
while($data = $res->fetch_assoc())
{
  $arr3['name'] = htmlentities($data['name'], ENT_QUOTES, 'UTF-8');
  $arr3['id'] = $data['id'];
  $out3[] = $arr3;
}

engine_head(lang('pages'));
temp::HTMassign('rmenu', $out3);
temp::display('control.pages');
engine_fin();