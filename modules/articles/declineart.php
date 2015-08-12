<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!CAN('stats_moderate', 0))
  denied();

$id = abs(intval(GET('id')));
$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`id` = "' . $id . '" ;');
if(!$res->num_rows)
  denied();

$data = $res->fetch_assoc();
if($data['type'] != 2) //Если статья не на модерации
  denied();

if(POST('decline'))
{
  //Отклоняем статью
  core::$db->query('UPDATE `ds_article` SET
     `type` = "1"
     WHERE `id`="' . $id . '" LIMIT 1;');

  func::notify(lang('art_decline'), lang('stat_added_toall'), core::$home . '/articles/onmoder', lang('continue'));
}
elseif(POST('submit'))
{
  engine_head(lang('art_decline'));
  temp::assign('id', $data['id']);
  temp::display('articles.declineart');
  engine_fin();
}