<?php
defined('DS_ENGINE') or die('web_demon laughs');
$id = abs(intval(GET('id')));

if(!core::$user_id)
  denied();

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `id` = "' . $id . '" AND `userid` = "'.core::$user_id.'" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();

if($id == core::$user_set['defprofile'])
  denied();

$error = array();

if(POST('submit'))
{
  $name = trim(POST('profile_name'));
  if(!$name)
    $error[] = lang('no_profile_name');

  if(!$error)
  {
    //Создаем профиль с текущим названием
    core::$db->query('UPDATE `ds_search_profiles` SET `pname`="'.core::$db->res(text::st($name)).'" WHERE `id` = "'.$id.'";');


    func::notify(lang('ren_item'), lang('new_item_renamed'), core::$home . '/user/cab', lang('continue'));
  }
}

engine_head(lang('ren_item'));
temp::assign('id', $data['id']);
temp::HTMassign('error', $error);
temp::assign('name', $data['pname']);
temp::display('user.renameprofile');
engine_fin();