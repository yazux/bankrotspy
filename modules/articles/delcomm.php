<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$mid = intval(abs(GET('mid')));
$page = intval(abs(GET('page')));

if(!$id OR !$mid)
  denied();

$res = core::$db->query('SELECT `ds_comm`.*, `ds_users`.`rights` FROM `ds_comm` LEFT JOIN `ds_users` ON `ds_comm`.`userid` = `ds_users`.`id` WHERE `ds_comm`.`id` = "' . $id . '" AND `ds_comm`.`mid` = "' . $mid . '";');
if(!$res->num_rows)
  denied();

$data = $res->fetch_assoc();
if((core::$user_id == $data['userid'] AND CAN('comm_self_delete', 0)) OR CAN('comm_delete', $data['rights']))
{
  if(POST('submit'))
  {
    //Удаляем коммент
    core::$db->query('DELETE FROM `ds_comm` WHERE `id`="' . $id . '" LIMIT 1');
    //Вычисляем и обновляем время последнего поста к статье
    $res = core::$db->query('SELECT * FROM `ds_comm` WHERE `module`="' . core::$module . '" AND `mid` = "' . $mid . '" ORDER BY `time` DESC LIMIT 1');
    $post = $res->fetch_array();
    $time = 0;
    if(isset($post['time']) AND $post['time'])
      $time = $post['time'];
    core::$db->query('UPDATE `ds_article` SET `comtime` = "' . $time . '" WHERE `id` = "' . $mid . '" LIMIT 1;');

    new nav;
    new comm(core::$module, 'view', $mid); //Класс для работы с камментами
    $now_post = comm::total();
    $return_page = ceil($now_post / nav::$kmess);
    if($page > $return_page)
      $page = $return_page;

    //Работаем дальше
    func::notify(lang('delete_comm'), lang('comm_deleted'), core::$home . '/' . text::st(GET('mod')) . '/post' . $mid . ($page ? '/page' . $page : '') . '#comms', lang('continue'));
  }

  engine_head(lang('delete_comm'));
  temp::assign('id', $data['id']);
  temp::assign('mid', $mid);
  temp::assign('page', $page);
  temp::assign('module', GET('mod'));
  temp::assign('action', GET('act'));
  temp::display('comms.delete');
  engine_fin();
}
else
  denied();