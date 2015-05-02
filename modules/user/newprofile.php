<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
   denied();

$error = array();

//Настройки по умолчанию
$set_table_array = defaultset::get();

function create_def_profile($tosave)
{
  core::$db->query('INSERT INTO `ds_search_profiles` SET
       `userid`="'.core::$user_id.'",
       `profile`="'.core::$db->res($tosave).'",
       `pname`="'.core::$db->res(lang('def_profile')).'"
        ;');

  core::$user_set['tabledata'] = core::$db->insert_id;
  core::$user_set['defprofile'] = core::$user_set['tabledata'];
  core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

  return true;
}

if(POST('submit'))
{
  $name = trim(POST('profile_name'));
  if(!$name)
    $error[] = lang('no_profile_name');

  if(!$error)
  {
    //Если нет профиля по умолчанию, создаем его
    if(!isset(core::$user_set['tabledata']) OR !core::$user_set['tabledata'])
      create_def_profile(json_encode($set_table_array));

    //Создаем профиль с текущим названием
    core::$db->query('INSERT INTO `ds_search_profiles` SET
       `userid`="'.core::$user_id.'",
       `profile`="'.core::$db->res(json_encode($set_table_array)).'",
       `pname`="'.core::$db->res(text::st($name)).'"
        ;');

    //Делаем новый профиль текущим:
    core::$user_set['tabledata'] = core::$db->insert_id;
    core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

    func::notify(lang('newprofile'), lang('new_item_added'), core::$home . '/user/cab', lang('continue'));

  }
}


engine_head(lang('newprofile'));
temp::HTMassign('error', $error);
temp::display('user.newprofile');
engine_fin();