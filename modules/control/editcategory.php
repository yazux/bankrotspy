<?php
defined('DS_ENGINE') or die('web_demon laughs');
$error = array();
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT *, `ds_maindata_category`.`name` FROM `ds_main_cat_spec` LEFT JOIN `ds_maindata_category` ON `ds_main_cat_spec`.`id` = `ds_maindata_category`.`id` WHERE `ds_main_cat_spec`.`id` = "'.$id.'" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();

if(POST('submit'))
{
  $name = text::st(POST('name'));
  if(!$name)
    $error[] = lang('no_name');
  $keys = text::st(POST('keywords_lot'));
  if(!$keys)
    $error[] = lang('no_keys');

  $addition = text::st(POST('addition_lot'));

  if(!$error)
  {
    if($name != $data['name'])
      core::$db->query('UPDATE `ds_maindata_category` SET `name` = "' . core::$db->res(text::st($name)) . '" WHERE `id` = "' . $id . '";');

    if($keys != $data['slova'])
    {
      $keywords = explode("\n", str_replace("\r\n", "\n", $keys));
      array_walk($keywords, 'trim_value');
      $keywords = implode("\r\n", $keywords);

      $addition = explode("\n", str_replace("\r\n", "\n", $addition));
      array_walk($addition, 'trim_value');
      $addition = implode("\r\n", $addition);
      core::$db->query('UPDATE `ds_main_cat_spec` SET `slova` = "' . core::$db->res(text::st($keywords)) . '", `def` = "' . core::$db->res(text::st($addition)) . '" WHERE `id` = "' . $id . '";');
    }

    func::notify(lang('edit_item'), lang('new_item_changed'), core::$home . '/control/categories', lang('continue'));
  }
}

function trim_value(&$value)
{
  $value = trim($value);
}

engine_head(lang('edit_item'));
temp::assign('name', $data['name']);
temp::assign('keywords', $data['slova']);
temp::assign('addition', $data['def']);
temp::assign('id', $data['id']);
temp::HTMassign('error', $error);
temp::display('control.editcategory');
engine_fin();