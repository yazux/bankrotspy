<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(core::$user_id)
  denied();
  
$key = GET('id');
if(mb_strlen($key) != 32)
  denied();

$res = core::$db->query('SELECT * FROM `ds_users_inactive` WHERE `key` = "'.core::$db->res($key).'"');
if(!$res->num_rows)
  denied();
else
{
  $data = $res->fetch_assoc();  
  $query = 'INSERT INTO `ds_users` SET
          `login`="'.core::$db->res($data['login']).'",
          `password`="'.$data['password'].'",
          `mail`="'.core::$db->res($data['mail']).'",
          `sex`="'.$data['sex'].'",
          `rights`="0",
          `time`="'.$data['time'].'",
          `ip`="'.core::$db->res(core::$ipl).'",
          `ua`="'.core::$db->res(core::$ua).'"
  ;';
  $query .= 'DELETE FROM `ds_users_inactive` WHERE `id` = "'.$data['id'].'";';
  
  core::$db->multi_query($query);
  core::$db->multi_free();
  
  func::notify(lang('activate'), lang('act_succ'), core::$home, lang('home'));  
}  
