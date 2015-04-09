<?php
defined('DS_ENGINE') or die('web_demon laughs');
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_tariffs` WHERE `id` = "' . $id . '" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();
if(POST('submit'))
{
  core::$db->query('DELETE FROM `ds_tariffs` WHERE `id` = "' . $id . '" LIMIT 1 ;');

  func::notify(lang('del_item'), lang('new_item_deleted'), core::$home . '/control/tset', lang('continue'));
}

engine_head(lang('del_item'));
temp::assign('id', $data['id']);
temp::assign('name', $data['name']);
temp::display('control.tarifdel');
engine_fin();
