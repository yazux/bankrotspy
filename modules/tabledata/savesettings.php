<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
  exit('1');

//Настройки по умолчанию
$set_table_array = defaultset::get();

$jset = POST('jsettings');
$jset = json_decode($jset, 1);
if(!$jset OR !is_array($jset))
  exit('2');

$save_set = array();
foreach($set_table_array AS $key => $value)
{
  if(isset($jset[$key]) AND is_array($jset[$key]) AND is_array($value))
  {
    $new_arr = array();
    foreach($jset[$key] AS $k => $v)
    {
      $new_arr[intval(abs($k))] = $v ?  1 : 0;
    }
    $save_set[$key] = $new_arr;
  }
  elseif(isset($jset[$key]) AND !is_array($jset[$key]) AND !is_array($value))
    $save_set[$key] = $jset[$key];
  else
    $save_set[$key] = $value;
}

core::$user_set['tabledata'] = json_encode($save_set);
core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');
