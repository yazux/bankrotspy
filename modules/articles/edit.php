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

$error = array();

$save_data = array();
$save_data['art_name'] = POST('art_name');
$save_data['art_text'] = POST('art_text');
$save_data['art_keys'] = POST('art_keywords');

new fload(
  $save_data,                                     //Указываем что сохранять
  core::$module,                                  // Модуль 'articles'
  core::$module . '-' . core::$action . '-' . $id,
  core::$home . '/articles/edit?id=' . $id,       // Устанавливаем куда возвращаться
  'edit',
  $id
);

if(isset($_POST['add_attachment']))
  fload::load_file();
elseif(isset($_POST['del_attachment']))
  fload::del_file(POST('del_attachment'));
elseif(POST('preview'))
{
  $art_name = POST('art_name');
  if(!$art_name)
    $error[] = lang('no_name');

  if(mb_strlen($art_name) > $max_name_lenght)
    $error[] = lang('name_so_long') . ' ' . $max_name_lenght;

  $art_text = POST('art_text');
  if(!$art_text)
    $error[] = lang('no_text');


  if(mb_strlen($art_text) > $max_stst_lenght)
    $error[] = lang('text_so_long') . ' ' . $max_stst_lenght;

  $keys = POST('art_keywords');
  if(!$keys)
    $error[] = lang('no_keys');

  $key_arr = explode(',', $keys);
  //Узнаем каких ключевых слов нет в базе
  $out_sql = '';
  $err_keys = false;
  $err_keys_min = false;
  $tobaze = array();
  foreach($key_arr AS $value)
  {
    $value = trim($value);
    if(mb_strlen($value) > $max_key_lenght)
      $err_keys = true;
    if(mb_strlen($value) < $min_key_lenght)
      $err_keys_min = true;
    $tobaze[] = text::st($value);
  }
  if($err_keys)
    $error[] = lang('keys_too_long') . ' ' . $max_key_lenght;

  if($err_keys_min)
    $error[] = lang('keys_too_small') . ' ' . $min_key_lenght;

  if(count($key_arr) > $max_col_keys)
    $error[] = lang('max_col_keys') . ' ' . $max_col_keys;

  if(!$error)
  {
    //Предосмотр
    $preview = true;
    fload::save_data();
    $arr['name'] = htmlentities($save_data['art_name'], ENT_QUOTES, 'UTF-8');
    $arr['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
    text::add_cache(text::presave($save_data['art_text']));
    $arr['text'] = text::out($save_data['art_text'], 0, 0);
    $arr['text'] = fload::replace_files($arr['text'], $id, core::$module);
    $arr['userid'] = $data['userid'];
    $arr['user'] = $data['autor'];

    $arr['keys'] = $tobaze;
  }
  else
  {
    fload::save_data();
  }
}
elseif(POST('exitpreview'))
{
  fload::save_data();
}
elseif(POST('public'))
{
  //Публикуем статью
  $art_name = POST('art_name');
  if(!$art_name)
    $error[] = lang('no_name');

  if(mb_strlen($art_name) > $max_name_lenght)
    $error[] = lang('name_so_long') . ' ' . $max_name_lenght;

  $art_text = POST('art_text');
  if(!$art_text)
    $error[] = lang('no_text');

  if(mb_strlen($art_text) > $max_stst_lenght)
    $error[] = lang('text_so_long') . ' ' . $max_stst_lenght;

  $keys = POST('art_keywords');
  if(!$keys)
    $error[] = lang('no_keys');

  $key_arr = explode(',', $keys);
  //Узнаем каких ключевых слов нет в базе
  $out_sql = '';
  $err_keys = false;
  $err_keys_min = false;
  $tobaze = array();
  foreach($key_arr AS $value)
  {
    $value = trim($value);
    if(mb_strlen($value) > $max_key_lenght)
      $err_keys = true;
    if(mb_strlen($value) < $min_key_lenght)
      $err_keys_min = true;
    if(!in_array($value, $art_keys))
    {
      $out_sql .= 'INSERT INTO `ds_art_keywords` SET `keyname` = "' . core::$db->res(text::st($value)) . '";';
    }
    $tobaze[] = text::st($value);
  }
  if($err_keys)
    $error[] = lang('keys_too_long') . ' ' . $max_key_lenght;

  if($err_keys_min)
    $error[] = lang('keys_too_small') . ' ' . $min_key_lenght;

  if(count($key_arr) > $max_col_keys)
    $error[] = lang('max_col_keys') . ' ' . $max_col_keys;

  if(!$error)
  {
    if(CAN('stats_edit', $data['rights']))
    {
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

      core::$db->query('UPDATE `ds_article` SET
          `name` = "' . core::$db->res($art_name) . '",
          `type` = "0",
          `keywords` = "' . core::$db->res(serialize($out_keys)) . '",
          `keywords_hide` = "",
          `time` = "' . time() . '",
          `text` = "' . core::$db->res($art_text) . '",
          `cache` = "' . core::$db->res(text::presave($art_text)) . '"
          WHERE `id`="' . $id . '" LIMIT 1;');

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

      fload::save_files();
      func::notify(lang('edit_st'), lang('stat_added_toall'), core::$home . '/articles/post' . $id, lang('continue'));
    }
    elseif(CAN('add_stats_moderate', 0) AND $data['userid']==core::$user_id AND $data['type'] == 1)
    {
      core::$db->query('UPDATE `ds_article` SET
          `name` = "' . core::$db->res($art_name) . '",
          `type` = "2",
          `keywords_hide` = "' . core::$db->res(serialize($tobaze)) . '",
          `text` = "' . core::$db->res($art_text) . '",
          `cache` = "' . core::$db->res(text::presave($art_text)) . '"
          WHERE `id`="' . $id . '" LIMIT 1;');

      fload::save_files();
      func::notify(lang('edit_st'), lang('stat_add_to_mod'), core::$home . '/articles/post' . $id, lang('continue'));
    }
  }
  else
  {
    fload::save_data();
  }
}
elseif(POST('submit') AND $data['type'])
{
  //Сохраняем статью в черновик
  $art_name = POST('art_name');
  if(!$art_name)
    $error[] = lang('no_name');

  if(mb_strlen($art_name) > $max_name_lenght)
    $error[] = lang('name_so_long') . ' ' . $max_name_lenght;

  $art_text = POST('art_text');
  if(!$art_text)
    $error[] = lang('no_text');

  if(mb_strlen($art_text) > $max_stst_lenght)
    $error[] = lang('text_so_long') . ' ' . $max_stst_lenght;

  $keys = POST('art_keywords');
  if(!$keys)
    $error[] = lang('no_keys');

  $key_arr = explode(',', $keys);
  //Узнаем каких ключевых слов нет в базе
  $out_sql = '';
  $err_keys = false;
  $err_keys_min = false;
  $tobaze = array();
  foreach($key_arr AS $value)
  {
    $value = trim($value);
    if(mb_strlen($value) > $max_key_lenght)
      $err_keys = true;
    if(mb_strlen($value) < $min_key_lenght)
      $err_keys_min = true;
    $tobaze[] = text::st($value);
  }
  if($err_keys)
    $error[] = lang('keys_too_long') . ' ' . $max_key_lenght;

  if($err_keys_min)
    $error[] = lang('keys_too_small') . ' ' . $min_key_lenght;

  if(count($key_arr) > $max_col_keys)
    $error[] = lang('max_col_keys') . ' ' . $max_col_keys;

  if(!$error)
  {
    core::$db->query('UPDATE `ds_article` SET
          `name` = "' . core::$db->res($art_name) . '",
          `keywords_hide` = "' . core::$db->res(serialize($tobaze)) . '",
          `text` = "' . core::$db->res($art_text) . '",
          `cache` = "' . core::$db->res(text::presave($art_text)) . '"
          WHERE `id`="' . $id . '" LIMIT 1;');

    fload::save_files();
    func::notify(lang('edit_st'), lang('stat_saved'), core::$home . '/articles/post' . $id, lang('continue'));
  }
  else
  {
    fload::save_data();
  }
}
elseif(POST('submit') AND !$data['type'])
{
  //Обновляем уже добавленную статью
  $art_name = POST('art_name');
  if(!$art_name)
    $error[] = lang('no_name');

  if(mb_strlen($art_name) > $max_name_lenght)
    $error[] = lang('name_so_long') . ' ' . $max_name_lenght;

  $art_text = POST('art_text');
  if(!$art_text)
    $error[] = lang('no_text');

  if(mb_strlen($art_text) > $max_stst_lenght)
    $error[] = lang('text_so_long') . ' ' . $max_stst_lenght;

  $keys = POST('art_keywords');
  if(!$keys)
    $error[] = lang('no_keys');

  $key_arr = explode(',', $keys);
  //Узнаем каких ключевых слов нет в базе
  $out_sql = '';
  $err_keys = false;
  $err_keys_min = false;
  $tobaze = array();
  foreach($key_arr AS $value)
  {
    $value = trim($value);
    if(mb_strlen($value) > $max_key_lenght)
      $err_keys = true;
    if(mb_strlen($value) < $min_key_lenght)
      $err_keys_min = true;
    if(!in_array($value, $art_keys))
    {
      $out_sql .= 'INSERT INTO `ds_art_keywords` SET `keyname` = "' . core::$db->res(text::st($value)) . '";';
    }
    $tobaze[] = text::st($value);
  }
  if($err_keys)
    $error[] = lang('keys_too_long') . ' ' . $max_key_lenght;

  if($err_keys_min)
    $error[] = lang('keys_too_small') . ' ' . $min_key_lenght;

  if(count($key_arr) > $max_col_keys)
    $error[] = lang('max_col_keys') . ' ' . $max_col_keys;

  if(!$error)
  {
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

    core::$db->query('UPDATE `ds_article` SET
          `name` = "' . core::$db->res($art_name) . '",
          `id_mod` = "' . core::$db->res(core::$user_id) . '",
          `modname` = "' . core::$db->res(core::$user_name) . '",
          `keywords` = "' . core::$db->res(serialize($out_keys)) . '",
          `lastmod` = "' . time() . '",
          `text` = "' . core::$db->res($art_text) . '",
          `cache` = "' . core::$db->res(text::presave($art_text)) . '"
          WHERE `id`="' . $id . '" LIMIT 1;');

    //добаляем в таблицу связку тег=>ид статьи
    if(!$data['keywords'])
      $data['keywords'] = serialize(array());
    $to_del = array();
    foreach($art_keys AS $key => $value)
    {
      if(in_array($value, $tobaze) AND in_array($key, unserialize($data['keywords'])))
      {
      }
      elseif(in_array($value, $tobaze) AND !in_array($key, unserialize($data['keywords'])))
        $out_sql .= 'INSERT INTO `ds_art_keytable` SET `keyid` = "' . $key . '", `statid` = "' . $id . '";';
      elseif(!in_array($value, $tobaze) AND in_array($key, unserialize($data['keywords'])))
      {
        $out_sql .= 'DELETE FROM `ds_art_keytable` WHERE `keyid` = "' . $key . '" AND `statid` = "' . $id . '";';
        $to_del[] = $key;
      }
    }
    if($out_sql)
    {
      core::$db->multi_query($out_sql);
      core::$db->multi_free();
    }

    del_tags($to_del);

    fload::save_files();
    func::notify(lang('edit_st'), lang('stat_saved'), core::$home . '/articles/post' . $id, lang('continue'));
  }
  else
  {
    fload::save_data();
  }
}

$cached_id = uscache::get('fload_edit_postid');

//Получаем сообщения пришедшие от загрузчика
$rem1 = uscache::get('message_article');
if($rem1 and $cached_id == $id)
{
  $error[] = $rem1;
  uscache::del('message_article');
}

engine_head(lang('edit_st'));

//Если был предосмотр
if(isset($preview))
{
  temp::assign('preview', '1');
  temp::HTMassign('arr', $arr);
}

//Восстанавливаем данные
$rem = array();
if(uscache::ex('fload_edit_uinid') AND uscache::ex('fload_edit_save_data') AND (uscache::get('fload_edit_uinid') == core::$module . '-' . core::$action . '-' . $id))
{
  $rem = unserialize(uscache::get('fload_edit_save_data'));
  if(isset($rem['art_name']))
    temp::assign('name', $rem['art_name']);
  if(isset($rem['art_text']))
    temp::assign('text', $rem['art_text']);
  if(isset($rem['art_keys']))
    temp::assign('text_keys', $rem['art_keys']);
}
else
{
  temp::assign('name', POST('art_name') ? POST('art_name') : $data['name']);
  temp::assign('text', POST('art_text') ? POST('art_text') : $data['text']);
  temp::assign('text_keys', POST('art_keywords') ? POST('art_keywords') : ($data['type'] ? implode(', ', unserialize($data['keywords_hide'])) : keys2text($data['keywords'])));
}

$out = fload::get_loaded();
if($out)
  temp::assign('att_true', 1);
temp::HTMassign('out', $out);

if($data['type'])
  temp::assign('hide_stat', 1);

temp::HTMassign('error', $error);
temp::assign('id', $data['id']);
temp::display('articles.edit');
engine_fin();
