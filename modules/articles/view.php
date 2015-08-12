<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(GET('id')));
$page = abs(intval(GET('page')));
$error = array();

$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`id` = "' . $id . '" ;');
if(!$res->num_rows)
  denied();

$data = $res->fetch_assoc();

//Запрещаем доступ к черновику другим пользователям
if($data['type'] AND $data['userid'] != core::$user_id AND !CAN('stats_moderate', 0))
  denied();

$arr = array();
$arr['name'] = htmlentities($data['name'], ENT_QUOTES, 'UTF-8');
text::add_cache($data['cache']);
$arr['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
$arr['text'] = text::out($data['text'], 0, $data['id']);
$arr['text'] = fload::replace_files($arr['text'], $data['id'], core::$module);
$arr['id'] = $data['id'];
$arr['userid'] = $data['userid'];
$arr['time'] = ds_time($data['time']);
$arr['user'] = $data['autor'];
$arr['rating'] = ($data['rating_plus'] - $data['rating_minus']);

if(core::$user_id)
{
  $res_fav = core::$db->query('SELECT * FROM `ds_art_favorites` WHERE `articleid` = "' . $id . '" AND `fav_user` = "' . core::$user_id . '" LIMIT 1;');
  if($res_fav->num_rows)
    $arr['favtime'] = 1;
}

if(!$data['type'])
{
  $keys = array();
  $keys_data = array();
  $keys_data = unserialize($data['keywords']);
  if($keys_data)
  {
    foreach($keys_data AS $kid)
    {
      if(isset($art_keys[$kid]))
        $keys[$kid] = $art_keys[$kid];
    }
  }
  $arr['keys'] = $keys;
}
else
{
  $arr['draft_keys'] = unserialize($data['keywords_hide']);
}

new nav(); //Постраничная навигация
new comm(core::$module, core::$action, $id); //Класс для работы с камментами

$total = comm::total();

if(core::$user_id AND core::$user_id != $data['userid'] AND !$data['type'])
{
  $query = 'SELECT * FROM `ds_art_vote` WHERE `stat_id` ="' . $id . '" AND `user_id` = "' . core::$user_id . '";';
  core::$db->multi_query($query);

  $req_vote = core::$db->store_result();
  if($req_vote->num_rows)
  {
    $vote = $req_vote->fetch_array();
    $v_type_id = $vote['id'];
    $v_type = $vote['type'];

    if(POST('add_plus'))
      $ztype = 1;
    elseif(POST('add_minus'))
      $ztype = 2;

    if(isset($ztype))
    {
      if($ztype == $v_type)
      {
        $sql = 'UPDATE `ds_article` SET `' . (($ztype == 1) ? 'rating_plus' : 'rating_minus') . '` = "' . ($data[(($ztype == 1) ? 'rating_plus' : 'rating_minus')] - 1) . '" WHERE `id`="' . $id . '" LIMIT 1;';
        $sql .= 'DELETE FROM `ds_art_vote` WHERE `id` = "' . $v_type_id . '";';
        core::$db->multi_query($sql);
        core::$db->multi_free();
        header('Location: ' . core::$home . '/articles/post' . $id . (comm::$page ? '/page' . comm::$page : ''));
        exit();
      }
      else
      {
        $sql = 'UPDATE `ds_article` SET `' . (($ztype == 1) ? 'rating_plus' : 'rating_minus') . '` = "' . ($data[(($ztype == 1) ? 'rating_plus' : 'rating_minus')] + 1) . '", `' . (($ztype == 1) ? 'rating_minus' : 'rating_plus') . '` = "' . ($data[(($ztype == 1) ? 'rating_minus' : 'rating_plus')] - 1) . '" WHERE `id`="' . $id . '" LIMIT 1;';
        $sql .= 'UPDATE `ds_art_vote` SET `type` ="' . $ztype . '" WHERE `id` = "' . $v_type_id . '";';
        core::$db->multi_query($sql);
        core::$db->multi_free();
        header('Location: ' . core::$home . '/articles/post' . $id . (comm::$page ? '/page' . comm::$page : ''));
        exit();
      }
    }
  }
  else
  {
    if(POST('add_plus'))
    {
      $sql = 'UPDATE `ds_article` SET `rating_plus` = "' . ($data['rating_plus'] + 1) . '" WHERE `id`="' . $id . '" LIMIT 1;';
      $sql .= 'INSERT INTO `ds_art_vote` SET `stat_id` = "' . $id . '", `user_id` = "' . core::$user_id . '", `type` = "1";';
      core::$db->multi_query($sql);
      core::$db->multi_free();
      header('Location: ' . core::$home . '/articles/post' . $id . (comm::$page ? '/page' . comm::$page : ''));
      exit();
    }
    elseif(POST('add_minus'))
    {
      $sql = 'UPDATE `ds_article` SET `rating_minus` = "' . ($data['rating_minus'] + 1) . '" WHERE `id`="' . $id . '" LIMIT 1;';
      $sql .= 'INSERT INTO `ds_art_vote` SET `stat_id` = "' . $id . '", `user_id` = "' . core::$user_id . '", `type` = "2";';
      core::$db->multi_query($sql);
      core::$db->multi_free();
      header('Location: ' . core::$home . '/articles/post' . $id . (comm::$page ? '/page' . comm::$page : ''));
      exit();
    }
  }
}

if(!$data['type'] AND core::$user_id AND CAN('create_comm', 0))
{
  if(POST('submit'))
  {
    $post = POST('msg');
    $error = comm::check_post($post);

    if(!$error)
    {
      //Добавляем пост
      $in_id = comm::add_post($post);
      //Обьявляем всем что в статье есть новые комменты
      core::$db->query('UPDATE `ds_article` SET `comtime` = "' . time() . '" WHERE `id` = "' . $id . '";');
      //Пользователю добавившему пост автоматом защитываем просмотр
      $comr = core::$db->query('SELECT * FROM `ds_art_comm_rdm` WHERE `modid` = "' . $data['id'] . '" AND `userid` = "' . core::$user_id . '";');
      if(!$comr->num_rows)
        core::$db->query('INSERT INTO `ds_art_comm_rdm` SET `modid` = "' . $data['id'] . '", `userid` = "' . core::$user_id . '", `rdmtime` = "' . time() . '";');
      else
        core::$db->query('UPDATE `ds_art_comm_rdm` SET `rdmtime` = "' . time() . '" WHERE `modid` = "' . $data['id'] . '" AND `userid` = "' . core::$user_id . '";');

      if(uscache::ex('comm_add_article'))
        uscache::del('comm_add_article');

      func::notify(lang('new_comm_mess'), lang('comm_added'), core::$home . '/articles/post' . $id . '/page' . comm::$page . '#comm' . $in_id);
    }
    else
    {
      uscache::rem('comm_add_article', $post);
      func::notify(lang('now_run_err'), implode('. ', $error), core::$home . '/articles/post' . $id . '/page' . comm::$page . '#crcomm');
    }
  }
  elseif(POST('preview'))
  {
    $post = POST('msg');
    $error = comm::check_post($post);

    if(!$error)
    {
      $preview = true;
      $com_pr['avatar'] = user::get_avatar(core::$user_id, core::$avtime, 1);
      $com_pr['online'] = user::is_online(time());
      $com_pr['from_login'] = core::$user_name;
      $com_pr['time'] = ds_time(time());
      text::add_cache(text::presave($post));
      $com_pr['text'] = text::out($post, 0, 0);
      $eng_right = user::get_rights();
      if(core::$rights > 0)
        $com_pr['rights'] = $eng_right[core::$rights];

    }
    else
    {
      uscache::rem('comm_add_article', $post);
      func::notify(lang('now_run_err'), implode('. ', $error), core::$home . '/articles/post' . $id . '/page' . comm::$page . '#crcomm');
    }
  }
  elseif(POST('exitpreview'))
  {
    $post = POST('msg');
  }
}

//отмечаем статью как прочитанную + комментрарии
if(core::$user_id)
{
  $nowtime = time();
  // Проверка
  $us_query = '';
  if($data['time'] > ($nowtime - (3 * 24 * 3600)))
    $us_query .= 'SELECT * FROM `ds_art_rdm` WHERE `artid` = "' . $data['id'] . '" AND `userid` = "' . core::$user_id . '";';
  if($data['comtime'] > ($nowtime - (3 * 24 * 3600)))
    $us_query .= 'SELECT * FROM `ds_art_comm_rdm` WHERE `modid` = "' . $data['id'] . '" AND `userid` = "' . core::$user_id . '";';

  if($us_query)
  {
    $fin_sql = '';
    core::$db->multi_query($us_query);
    if($data['time'] > ($nowtime - (3 * 24 * 3600)))
    {
      $creq = core::$db->store_result();
      if(!$creq->num_rows)
        $fin_sql .= 'INSERT INTO `ds_art_rdm` SET `artid` = "' . $data['id'] . '", `userid` = "' . core::$user_id . '", `time` = "' . time() . '";';
    }

    if(($data['time'] > ($nowtime - (3 * 24 * 3600))) AND ($data['comtime'] > ($nowtime - (3 * 24 * 3600))))
      core::$db->next_result();

    if($data['comtime'] > ($nowtime - (3 * 24 * 3600)))
    {
      $creq = core::$db->store_result();
      if(!$creq->num_rows)
      {
        $fin_sql .= 'INSERT INTO `ds_art_comm_rdm` SET `modid` = "' . $data['id'] . '", `userid` = "' . core::$user_id . '", `rdmtime` = "' . time() . '";';
        comm::$lastview = ($nowtime - (3 * 24 * 3600));
      }
      else
      {
        $cdata = $creq->fetch_array();
        if($data['comtime'] > $cdata['rdmtime'])
          $fin_sql .= 'UPDATE `ds_art_comm_rdm` SET `rdmtime` = "' . time() . '" WHERE `modid` = "' . $data['id'] . '" AND `userid` = "' . core::$user_id . '";';
        //Добавляем в пост последний визит
        comm::$lastview = $cdata['rdmtime'];
      }
    }

    //Если все таки нужно отметить, то делаем запрос
    if($fin_sql)
    {
      core::$db->multi_query($fin_sql);
      core::$db->multi_free();
    }
  }
}

//Получаем сообщения
$rem1 = uscache::get('message_comm');
if($rem1)
{
  $error[] = $rem1;
  uscache::del('message_comm');
}

// Выводим комментарии
$comms = comm::view(nav::$start, nav::$kmess);

engine_head($data['name']);
temp::HTMassign('out', $arr);

if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
  temp::assign('can_cr_stat', 1);
if(CAN('stats_edit', $data['rights']) OR ($data['userid']==core::$user_id AND $data['type'] == 1 AND CAN('add_stats_moderate', 0)))
  temp::assign('can_ed_stat', 1);
if(CAN('stats_delete', $data['rights']) OR ($data['userid']==core::$user_id AND $data['type'] == 1 AND CAN('add_stats_moderate', 0)))
  temp::assign('can_del_stat', 1);
if(isset($v_type))
  temp::assign('vtype', $v_type);

if(isset($preview))
{
  temp::assign('comm_prev_local', '1');
  temp::HTMassign('com_pr', $com_pr);
}
temp::assign('page', $page);
if(isset($post))
  temp::assign('text_to_prev', $post);

//ХЗ что оно делает
//if(uscache::ex('comm_add_article'))
//  temp::assign('text', uscache::get('comm_add_article'));

temp::HTMassign('com', $comms);
temp::assign('total', $total);
temp::assign('id', $id);
if(core::$user_id and CAN('create_comm', 0))
  temp::assign('its_user', core::$user_id);

temp::HTMassign('error', $error);
if($data['type'] == 2)
  temp::assign('onmoder_stat', 1);
if($data['type'] == 1 AND (CAN('stats_edit', $data['rights']) OR ($data['userid']==core::$user_id AND $data['type'] == 1 AND CAN('add_stats_moderate', 0))))
  temp::assign('ondraft_stat', 1);
if($data['type'] == 2 AND !CAN('stats_moderate', 0))
  temp::assign('onmoder_stat_head', 1);

if(CAN('stats_moderate', 0) AND $data['type'] == 2)
  temp::assign('onmoder_show_buttons', 1);

if($data['type'])
  temp::assign('hidden_stat', 1);
else
{
  nav::$rewrite = '/page';
  nav::$postfix = '#comms';
  temp::HTMassign('navigation', nav::display($total, core::$home . '/articles/post' . $id));
}
temp::display('articles.view');
engine_fin();
