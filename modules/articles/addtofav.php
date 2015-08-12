<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(POST('art_id')));
if(!$id or !core::$user_id)
  denied();

$res_art = core::$db->query('SELECT * FROM `ds_article` WHERE `id` = "'.core::$db->res($id).'" AND `ds_article`.`type` = "0" LIMIT 1;');
if(!$res_art->num_rows)
  denied();

$res = core::$db->query('SELECT * FROM `ds_art_favorites` WHERE `articleid` = "'.$id.'" AND `fav_user` = "'.core::$user_id.'" LIMIT 1;');
if(!$res->num_rows)
{
  core::$db->query('INSERT INTO `ds_art_favorites` SET `articleid` = "'.$id.'", `fav_user` = "'.core::$user_id.'", `favtime` = "'.time().'";');
  func::notify(lang('adding_fav'), lang('fav_added'), core::$home.'/articles/post'.$id, lang('continue'));
}
else
{
  core::$db->query('DELETE FROM `ds_art_favorites` WHERE `articleid` = "'.$id.'" AND `fav_user` = "'.core::$user_id.'" LIMIT 1;');
  func::notify(lang('deleting_fav'), lang('fav_deleted'), core::$home.'/articles/post'.$id, lang('continue'));
}