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

if(POST('agree'))
{
  //Определяемся с тегами
  $key_arr = unserialize($data['keywords_hide']);
  foreach($key_arr AS $value)
  {
    $value = trim($value);
    if(!in_array($value, $art_keys))
    {
      $out_sql .= 'INSERT INTO `ds_art_keywords` SET `keyname` = "' . core::$db->res(text::st($value)) . '";';
    }
    $tobaze[] = text::st($value);
  }
  //Если нет, то добавляем их
  if($out_sql)
  {
    core::$db->multi_query($out_sql);
    core::$db->multi_free();
    $out_sql = '';
  }
  //Заново запрашиваем слова и выясняем ид каждого в таблице
  $art_keys = get_keys();
  $out_keys = array();
  foreach($art_keys AS $key => $value)
  {
    if(in_array($value, $tobaze))
      $out_keys[] = $key;
  }
  //добаляем в таблицу связку тег=>ид статьи
  foreach($art_keys AS $key => $value)
  {
    if(in_array($value, $tobaze))
      $out_sql .= 'INSERT INTO `ds_art_keytable` SET `keyid` = "' . $key . '", `statid` = "' . $id . '";';
  }
  if($out_sql)
  {
    core::$db->multi_query($out_sql);
    core::$db->multi_free();
  }

  //Одобряем статью
  core::$db->query('UPDATE `ds_article` SET
     `type` = "0",
     `keywords` = "' . core::$db->res(serialize($out_keys)) . '",
     `keywords_hide` = "",
     `time` = "' . time() . '"
     WHERE `id`="' . $id . '" LIMIT 1;');

  $moder_count = core::$db->query('SELECT COUNT(*) FROM `ds_article` WHERE `type` = "2";')->count();
  if($moder_count)
    $return = core::$home . '/articles/onmoder';
  else
    $return = core::$home . '/articles/post'.$id;

  func::notify(lang('art_agree'), lang('stat_added_toall'), $return, lang('continue'));
}
elseif(POST('submit'))
{
  engine_head(lang('art_agree'));
  temp::assign('id', $data['id']);
  temp::display('articles.acceptart');
  engine_fin();
}