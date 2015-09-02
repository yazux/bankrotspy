<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
if(!$id)
  denied();

$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`id` = "' . $id . '" ;');
if(!$res->num_rows)
  denied();

$data = $res->fetch_assoc();
if(!CAN('stats_edit', $data['rights']) AND !($data['userid']==core::$user_id AND $data['type'] == 1 AND CAN('add_stats_moderate', 0)))
  denied();

if(POST('agree'))
{
  if(CAN('stats_edit', $data['rights']))
  {
    //Публикуем статью
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

    func::notify(lang('art_agree'), lang('stat_added_toall'), core::$home . '/articles/post'.$id, lang('continue'));
  }
  else
  {
    //Добавляем статью на модерацию
    core::$db->query('UPDATE `ds_article` SET
     `type` = "2"
     WHERE `id`="' . $id . '" LIMIT 1;');

    func::notify(lang('art_agree'), lang('stat_added_onmoder'), core::$home . '/articles/post'.$id, lang('continue'));
  }
}
elseif(POST('submit'))
{
  engine_head(lang('art_agree'));
  if(!CAN('stats_edit', $data['rights']))
    temp::assign('onmoder_stat', 1);
  temp::assign('id', $data['id']);
  temp::display('articles.public');
  engine_fin();
}