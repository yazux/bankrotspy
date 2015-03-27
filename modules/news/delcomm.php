<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$mid = intval(abs(GET('mid')));
$page = intval(abs(GET('page')));

if($id AND $mid)
{
  $res = core::$db->query('SELECT `ds_comm`.*, `ds_users`.`rights` FROM `ds_comm` LEFT JOIN `ds_users` ON `ds_comm`.`userid` = `ds_users`.`id` WHERE `ds_comm`.`id` = "'.$id.'" AND `ds_comm`.`mid` = "'.$mid.'";');  
  if($res->num_rows)
  {
    $data = $res->fetch_assoc();
    if((core::$user_id == $data['userid'] AND CAN('comm_self_delete', 0)) OR CAN('comm_delete', $data['rights']))
    {
      if(POST('submit'))
      {
         //Удаляем коммент
         core::$db->query('DELETE FROM `ds_comm` WHERE `id`="'.$id.'" LIMIT 1');
         //Вычисляем и обновляем время последнего поста к статье
         $res = core::$db->query('SELECT * FROM `ds_comm` WHERE `module`="'.core::$module.'" AND `mid` = "'.$mid.'" ORDER BY `time` DESC LIMIT 1');
         $post = $res->fetch_array();
         if(!isset($post['time']) OR !$post['time'])
            $time =  0;
         else
            $time = $post['time'];
         core::$db->query('UPDATE `ds_news` SET `comtime` = "'.$time.'" WHERE `id` = "'.$mid.'" LIMIT 1;');
         //Работаем дальше 
         uscache::rem('mess_head', lang('delete_comm'));
         uscache::rem('mess_body', lang('comm_deleted')); 
         header('Location:'.core::$home.'/'.text::st(GET('mod')).'/'.text::st(GET('act')).'?id='.$mid.($page ? '&page='.$page : ''));
         exit(); 
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
  }
  else
    denied();   
}
else
  denied();
