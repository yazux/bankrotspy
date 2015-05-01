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

core::$db->query('DELETE FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res($name).'" AND `userid` = "'.core::$user_id.'" LIMIT 1;');

//Делаем стандартный профиль текущим:
core::$user_set['tabledata'] = core::$user_set['defprofile'];
core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

//Оповещение
uscache::rem('mess_head', lang('del_profile'));
uscache::rem('mess_body', lang('del_profile_ok'));

echo 'ok';