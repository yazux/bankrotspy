<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$error = array();
if($id and core::$user_id)
{
  $res = core::$db->query('SELECT * FROM  `ds_users` WHERE `id` = "'.core::$db->res($id).'" LIMIT 1');  
  if($res->num_rows)
  {
    $data = $res->fetch_array();
    if(CAN('del_user', $data['rights']) AND $data['id'] != core::$user_id)
    {
      if(POST('delete'))
      {
        core::$db->query('INSERT INTO `ds_depr_emails` SET `mail` = "'.core::$db->res($data['mail']).'";');  
        core::$db->query('DELETE FROM `ds_users` WHERE `id` = "'.$id.'" LIMIT 1;');  
        func::notify(lang('del_user'), lang('stat_deleted'), core::$home.'/user/allusers', lang('continue'));  
      }    
        
      engine_head(lang('del_user'));
      temp::assign('name', $data['login']);
      temp::assign('id', $data['id']);
      temp::display('user.delete');
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


