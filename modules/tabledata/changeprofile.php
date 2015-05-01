<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
  exit('1');

$name = abs(intval(POST('profile_id')));
if(!$name)
  exit('No id!');

$res = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res($name).'" AND `userid` = "'.core::$user_id.'";');
if(!$res->num_rows)
  exit('Profile not found!');

//Делаем новый профиль текущим:
core::$user_set['tabledata'] = $name;
core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

echo 'ok';