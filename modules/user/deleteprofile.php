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

if(POST('submit'))
{
  core::$db->query('DELETE FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res($id).'" AND `userid` = "'.core::$user_id.'" LIMIT 1;');

  //Делаем стандартный профиль текущим:
  core::$user_set['tabledata'] = core::$user_set['defprofile'];
  core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');


  func::notify(lang('del_item'), lang('new_item_deleted'), core::$home . '/user/cab', lang('continue'));
}

engine_head(lang('del_item'));
temp::assign('id', $data['id']);
temp::display('user.deleteprofile');
engine_fin();