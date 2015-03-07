<?php
defined('DS_ENGINE') or die('web_demon laughs');

$key = GET('id');
if(mb_strlen($key) != 32)
  denied();

$res = core::$db->query('SELECT * FROM `ds_chmail` WHERE `key` = "'.core::$db->res($key).'"');
if(!$res->num_rows)
  denied();
else
{
  $data = $res->fetch_assoc();  
  $query = 'UPDATE `ds_users` SET `mail`="'.core::$db->res($data['mail']).'" WHERE `id` = "'.$data['user_id'].'" LIMIT 1;';
  $query .= 'INSERT INTO `ds_depr_emails` SET `mail`="'.$data['oldmail'].'";';
  $query .= 'DELETE FROM `ds_chmail` WHERE `id` = "'.$data['id'].'" LIMIT 1;';
  
  core::$db->multi_query($query);
  core::$db->multi_free();
  
  func::notify(lang('activate'), lang('act_succ'), core::$home, lang('continue'));  
}  
