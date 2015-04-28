<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(POST('submit'))
{
  core::$db->query('DELETE FROM `ds_maindata_bad_data` ;');

  func::notify(lang('del_item'), lang('new_item_deleted'), core::$home . '/parserstat/errlots', lang('continue'));
}

engine_head(lang('del_item'));
temp::display('parserstat.clearlots');
engine_fin();