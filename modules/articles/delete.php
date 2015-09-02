<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
if(!$id) denied();

$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`id` = "'.$id.'" ;');
if(!$res->num_rows) denied();

$data = $res->fetch_assoc();
if(!CAN('stats_delete', $data['rights'])) denied();

if(POST('submit'))
{
  core::$db->query('DELETE FROM `ds_article` WHERE `id`="'.$id.'" LIMIT 1;');
  fload::del_files($id, 'articles');
  core::$db->query('DELETE FROM `ds_art_keytable` WHERE `statid` = "'.$id.'";');
  del_tags(unserialize($data['keywords']));
  //Удаляем статью у всех из избранных
  core::$db->query('DELETE FROM `ds_art_favorites` WHERE `articleid` = "'.$id.'";');
  //Удаляем комментарии
  comm::del_comms($id, 'articles');
  func::notify(lang('delete_st'), lang('stat_deleted'), core::$home.'/articles/allarticles', lang('continue'));
}
  
engine_head(lang('delete_st'));  
temp::assign('id', $data['id']);  
temp::display('articles.delete');
engine_fin();