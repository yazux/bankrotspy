<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(POST('submit'))
{
  $art_text = POST('mess');
  core::$db->query('UPDATE `ds_reg_page` SET
          `text` = "'.core::$db->res($art_text).'",
          `cache` = "'.core::$db->res(text::presave($art_text)).'"
          WHERE `id`="1" LIMIT 1;');

  func::notify(lang('text_reg'), lang('settings_saved'), core::$home.'/control/regmenu', lang('continue'));
}

$res = core::$db->query('SELECT * FROM  `ds_reg_page` WHERE `id` = "1";');
$data = $res->fetch_assoc();
$error = array();

engine_head(lang('text_reg'));
temp::assign('text',$data['text']);
temp::display('control.regmenu');
engine_fin();