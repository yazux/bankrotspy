<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$mid = intval(abs(GET('mid')));
$page = intval(abs(GET('page')));
$error = array();
$post = POST('post');

if(!$id OR !$mid)
  denied();

$res = core::$db->query('SELECT `ds_comm`.*, `ds_users`.`rights`, `ds_users`.`avtime`, `ds_users`.`lastvisit` FROM `ds_comm` LEFT JOIN `ds_users` ON `ds_comm`.`userid` = `ds_users`.`id` WHERE `ds_comm`.`id` = "' . $id . '" AND `ds_comm`.`mid` = "' . $mid . '";');
if(!$res->num_rows)
  denied();

$data = $res->fetch_assoc();


if((core::$user_id == $data['userid'] AND CAN('comm_self_edit', 0)) OR CAN('comm_delete', $data['rights']))
{ }
else
  denied();

new comm(text::st(GET('mod')), text::st(GET('act')), $id, $data['rights'], $data['userid']); //Класс для работы с камментами

if(POST('preview'))
{
  $error = array();
  if(!$post)
    $error[] = lang('no_comm');

  if(mb_strlen($post) > comm::$post_long)
    $error[] = lang('to_long_comm');

  if(!$error)
  {
    $preview = true;
    $com = array();
    $com['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
    $com['online'] = user::is_online($data['lastvisit']);
    $com['from_login'] = $data['username'];
    $com['time'] = ds_time($data['time']);
    text::add_cache(text::presave($post));
    $com['text'] = text::out($post, 0, 0);

    $com['useredit'] = core::$user_name;
    $com['editid'] = core::$user_id;
    $com['lastedit'] = ds_time(time());
    $com['numedit'] = ($data['numedit'] + 1);
  }
}
elseif(POST('exitpreview'))
{
}
elseif(POST('submit'))
{
  $error = comm::check_post($post);
  if(!$error)
  {
    core::$db->query('UPDATE `ds_comm` SET
             `useredit` = "' . core::$db->res(core::$user_name) . '",
             `editid` = "' . core::$user_id . '",
             `lastedit` = "' . time() . '",
             `numedit` = "' . ($data['numedit'] + 1) . '",
             `text` = "' . core::$db->res($post) . '",
             `cache` = "' . core::$db->res(text::presave($post)) . '"
              WHERE `id`="' . $id . '" LIMIT 1;');

    if(text::st(GET('mod')) == 'articles')
      $retpath = core::$home . '/articles/post' . $mid . ($page ? '/page' . $page : '') . '#comm' . $id;
    else
      $retpath = core::$home . '/' . text::st(GET('mod')) . '/' . text::st(GET('act')) . '?id=' . $mid . ($page ? '&page=' . $page : '') . '#comm' . $id;

    func::notify(lang('edit_comm_d'), lang('comm_edited'), $retpath, lang('continue'));
  }
}

if($error)
{
  uscache::rem('mess_head', lang('edit_comm_d'));
  uscache::rem('mess_body', implode('. ', $error));
}

engine_head(lang('edit_comm_d'));
//Если был предосмотр
if(isset($preview))
{
  temp::assign('comm_prev', '1');
  temp::HTMassign('com', $com);
}
if(text::st(GET('mod')) == 'articles')
  $backurl = core::$home . '/articles/post' . $mid . ($page ? '/page' . $page : '') . '#comm' . $id;
else
  $backurl = core::$home . '/' . text::st(GET('mod')) . '/' . text::st(GET('act')) . '?id=' . $mid . ($page ? '&page=' . $page : '') . '#comm' . $id;
temp::assign('burl', $backurl);

temp::assign('id', $data['id']);
//temp::HTMassign('error', $error);
temp::assign('text', ($post ? $post : $data['text']));
temp::assign('mid', $mid);
temp::assign('page', $page);
temp::assign('module', GET('mod'));
temp::assign('action', GET('act'));
temp::display('comms.edit');
engine_fin();




