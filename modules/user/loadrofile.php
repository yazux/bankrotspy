<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
  denied();

$name = abs(intval(GET('id')));
if(!$name)
  denied();

$res = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res($name).'" AND `userid` = "'.core::$user_id.'";');
if(!$res->num_rows)
  denied();

//Делаем новый профиль текущим:
core::$user_set['tabledata'] = $name;
core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

//Оповещение
uscache::rem('mess_head', lang('del_profile'));
uscache::rem('mess_body', lang('del_profile_ok'));

header('Location:'.core::$home);