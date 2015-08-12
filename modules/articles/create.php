<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!CAN('stats_create', 0) AND !CAN('add_stats_moderate', 0))
  denied();

$error = array();

$save_data = array();
$save_data['art_name'] = POST('art_name');
$save_data['art_text'] = POST('art_text');
$save_data['art_keys'] = POST('art_keywords');

new fload(
  $save_data,                           //Указываем что сохранять
  core::$module,                        // Модуль 'articles'
  core::$module . '-' . core::$action,  //uid по нему восстанавливаются данные.
  core::$home . '/articles/create',     // Устанавливаем куда возвращаться
  'create'
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
    if(file_exists('images/avatars/' . core::$avtime . '_' . core::$user_id . '_small.png'))
      $arr['avatar'] = '/images/avatars/' . core::$avtime . '_' . core::$user_id . '_small.png';
    else
      $arr['avatar'] = '';
    text::add_cache(text::presave($save_data['art_text']));
    $arr['text'] = text::out($save_data['art_text'], 0, 0);
    $arr['text'] = fload::replace_files($arr['text'], fload::$att, core::$module, 'att_id');
    $arr['userid'] = core::$user_id;
    $arr['user'] = core::$user_name;
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
elseif(POST('draft'))
{
  //Добавляем в черновик
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
    //добавляем в базу статью
    core::$db->query('INSERT INTO `ds_article` SET
        `name` = "' . core::$db->res($art_name) . '",
        `type` = "1",
        `userid` = "' . core::$db->res(core::$user_id) . '",
        `autor` = "' . core::$db->res(core::$user_name) . '",
        `keywords_hide` = "' . core::$db->res(serialize($tobaze)) . '",
        `time` = "' . time() . '",
        `text` = "' . core::$db->res($art_text) . '",
        `cache` = "' . core::$db->res(text::presave($art_text)) . '";');
    //ид статьи
    $insert_id = core::$db->insert_id;
    //Сохраняем файлы
    fload::save_files($insert_id);

    //Сообщаем что все прошло удачно
    func::notify(lang('create_stat'), lang('stat_added_to_draft'), core::$home . '/articles/post' . $insert_id, lang('continue'));
  }
  else
  {
    fload::save_data();
  }
}
elseif(POST('submit'))
{
  //добавляем статью
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
    if(CAN('stats_create', 0))
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

      //добавляем в базу статью
      core::$db->query('INSERT INTO `ds_article` SET
        `name` = "' . core::$db->res($art_name) . '",
        `type` = "0",
        `userid` = "' . core::$db->res(core::$user_id) . '",
        `autor` = "' . core::$db->res(core::$user_name) . '",
        `keywords` = "' . core::$db->res(serialize($out_keys)) . '",
        `time` = "' . time() . '",
        `text` = "' . core::$db->res($art_text) . '",
        `cache` = "' . core::$db->res(text::presave($art_text)) . '";');

      //ид статьи
      $insert_id = core::$db->insert_id;
      //Сохраняем файлы
      fload::save_files($insert_id);
      //добаляем в таблицу связку тег=>ид статьи
      foreach($art_keys AS $key => $value)
      {
        if(in_array($value, $tobaze))
          $out_sql .= 'INSERT INTO `ds_art_keytable` SET `keyid` = "' . $key . '", `statid` = "' . $insert_id . '";';
      }
      core::$db->multi_query($out_sql);
      core::$db->multi_free();
      //Сообщаем что все прошло удачно
      func::notify(lang('create_stat'), lang('stat_added'), core::$home . '/articles/post' . $insert_id, lang('continue'));

    }
    elseif(CAN('add_stats_moderate', 0))
    {
      //добавляем в базу статью
      core::$db->query('INSERT INTO `ds_article` SET
        `name` = "' . core::$db->res($art_name) . '",
        `type` = "2",
        `userid` = "' . core::$db->res(core::$user_id) . '",
        `autor` = "' . core::$db->res(core::$user_name) . '",
        `keywords_hide` = "' . core::$db->res(serialize($tobaze)) . '",
        `time` = "' . time() . '",
        `text` = "' . core::$db->res($art_text) . '",
        `cache` = "' . core::$db->res(text::presave($art_text)) . '";');
      //ид статьи
      $insert_id = core::$db->insert_id;
      //Сохраняем файлы
      fload::save_files($insert_id);

      //Сообщаем что все прошло удачно
      func::notify(lang('create_stat'), lang('stat_added_onmoder'), core::$home . '/articles/allarticles');
    }
  }
  else
  {
    fload::save_data();
  }
}

engine_head(lang('create_stat'));
//Если был предосмотр
if(isset($preview))
{
  temp::assign('preview', '1');
  temp::HTMassign('arr', $arr);
}

//Получаем список загруженных файлов
$out = fload::get_loaded();
if($out)
  temp::assign('att_true', 1);

//Получаем сообщения пришедшие от загрузчика
$rem1 = uscache::get('message_article');
if($rem1)
{
  $error[] = $rem1;
  uscache::del('message_article');
}

//Восстанавливаем данные
$rem = array();
if(uscache::ex('fload_create_uinid') AND uscache::ex('fload_create_save_data') AND (uscache::get('fload_create_uinid') == core::$module . '-' . core::$action))
{
  $rem = unserialize(uscache::get('fload_create_save_data'));

  if(isset($rem['art_name']))
    temp::assign('name', $rem['art_name']);
  if(isset($rem['art_text']))
    temp::assign('text', $rem['art_text']);
  if(isset($rem['art_keys']))
    temp::assign('text_keys', $rem['art_keys']);
}

if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
  temp::assign('can_cr_stat', 1);
temp::HTMassign('out', $out);
temp::HTMassign('error', $error);
temp::display('articles.create');
engine_fin();


