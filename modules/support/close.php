<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$error = array();

if(!$id)
  denied();

$res = core::$db->query('SELECT `ds_support`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_support` LEFT JOIN `ds_users` ON `ds_support`.`userid` = `ds_users`.`id` WHERE `ds_support`.`id` = "'.$id.'" LIMIT 1;');
if($res->num_rows)
{
  $rs = $res->fetch_assoc();
  if(core::$user_id != $rs['userid'] AND !CAN('tech_support', 0))  
    denied();
  
  if(POST('submit'))
  {
    if(!$rs['closed'])
    {
         core::$db->query('UPDATE `ds_support` SET `newtime` = "' . time() . '", `closed` = "1" WHERE `id` = "' . $id . '";');
        
         //Работаем дальше 
         uscache::rem('mess_head', lang('mess_head'));
         uscache::rem('mess_body', lang('mess_osn')); 
         header('Location:'.core::$home.'/support');
         exit(); 
    }
    else
      denied();
  }

  engine_head(lang('del_item'));
  temp::assign('id', $id);
  temp::display('support.close');
  engine_fin();
}
else
  denied();