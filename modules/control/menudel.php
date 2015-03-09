<?php
defined('DS_ENGINE') or die('web_demon laughs');
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_menu` WHERE `id` = "' . $id . '" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();
if(POST('submit'))
{
  core::$db->query('DELETE FROM `ds_menu` WHERE `id` = "' . $id . '" LIMIT 1 ;');

  $tosql = '';
  $sect = core::$db->query('SELECT * FROM `ds_menu` ORDER BY `sort`;');
  if($sect->num_rows)
  {
    while($res = $sect->fetch_assoc())
    {
      if($res['sort'] > $data['sort'])
        $tosql .= 'UPDATE `ds_menu` SET `sort` = "' . ($res['sort'] - 1) . '" WHERE `id` = "' . $res['id'] . '";';
    }

    if($tosql)
    {
      core::$db->multi_query($tosql);
      core::$db->multi_free();
    }
  }

  func::notify(lang('del_item'), lang('new_item_deleted'), core::$home . '/control/menu', lang('continue'));
}

engine_head(lang('del_item'));
temp::assign('id', $data['id']);
temp::display('control.menudel');
engine_fin();
