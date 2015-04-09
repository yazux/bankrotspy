<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(POST('submit'))
{
  $merchant_key = text::st(POST('merchant_key'));
  $market_id = text::st(POST('market_id'));
  $market_prefix = text::st(POST('prefix'));
  $mess = text::st(POST('mess'));

  $query = 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($merchant_key).'" WHERE `key` = "merchant_key";';
  $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($market_id).'" WHERE `key` = "market_id";';
  $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($market_prefix).'" WHERE `key` = "market_prefix";';

  core::$db->multi_query($query);
  core::$db->multi_free();

  core::$db->query('UPDATE `ds_reg_page` SET
          `text` = "'.core::$db->res($mess).'",
          `cache` = "'.core::$db->res(text::presave($mess)).'"
          WHERE `id`="2" LIMIT 1;');

  func::notify(lang('t_settings'), lang('settings_saved'), core::$home . '/control/tariffs', lang('continue'));
}

$res = core::$db->query('SELECT * FROM  `ds_reg_page` WHERE `id` = "2";');
$data = $res->fetch_assoc();

engine_head(lang('t_settings'));
temp::HTMassign('merchant_key', core::$set['merchant_key']);
temp::HTMassign('market_id', core::$set['market_id']);
temp::HTMassign('market_prefix', core::$set['market_prefix']);
temp::assign('text',$data['text']);
temp::display('control.tariffs');
engine_fin();