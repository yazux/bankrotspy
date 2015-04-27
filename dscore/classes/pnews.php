<?php
defined('DS_ENGINE') or die('web_demon laughs');

class pnews
{
  public static function add($ptext)
  {
    core::$db->query('INSERT INTO `ds_platform_news` SET
      `text` = "'.core::$db->res($ptext).'",
      `cache` = "'.core::$db->res(text::presave($art_text)).'",
      `time` = "'.time().'"
       ;');
  }

}