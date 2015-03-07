<?php
defined('DS_ENGINE') or die('web_demon laughs');

$color = POST('color');

if(!preg_match('/^[0-9a-f]{6}$/i', $color))
{
  exit('no');
}
else
{
  if(core::$user_id)
  {
    core::$user_set['themecolor'] = $color;
    core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');
  }
  else
    $_SESSION['themecolor'] = $color;
  exit('yeas');
}


