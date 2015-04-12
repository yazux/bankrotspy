<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(POST('submit'))
{
  $site_domain = text::st(POST('site_domain'));
  $site_name = text::st(POST('site_name'));
  $site_keywords = text::st(POST('site_keywords'));
  $site_description = text::st(POST('site_description'));

  $query = 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_domain).'" WHERE `key` = "site_name";';
  $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_name).'" WHERE `key` = "site_name_main";';
  $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_description).'" WHERE `key` = "description";';
  $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_keywords).'" WHERE `key` = "keywords";';

  core::$db->multi_query($query);
  core::$db->multi_free();

  $art_text = POST('mess');
  core::$db->query('UPDATE `ds_reg_page` SET
          `text` = "'.core::$db->res($art_text).'",
          `cache` = "'.core::$db->res(text::presave($art_text)).'"
          WHERE `id`="1" LIMIT 1;');

  func::notify(lang('base_settings'), lang('settings_saved'), core::$home . '/control', lang('continue'));
}

$res = core::$db->query('SELECT * FROM  `ds_reg_page` WHERE `id` = "1";');
$data = $res->fetch_assoc();
$error = array();

engine_head(lang('base_settings'));
temp::HTMassign('site_domain', core::$set['site_name']);
temp::HTMassign('site_name', core::$set['site_name_main']);
temp::HTMassign('site_keywords', core::$set['keywords']);
temp::HTMassign('site_description', core::$set['description']);
temp::assign('text',$data['text']);
temp::display('control.index');
engine_fin();