<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
{
  $error = array();
    
  $key = GET('id');
  if(mb_strlen($key) != 32)
    denied(); 

  $res = core::$db->query('SELECT * FROM `ds_recpassword` WHERE `num` = "'.core::$db->res($key).'" LIMIT 1;');
  if(!$res->num_rows)
    denied();
  else
  {
    $data = $res->fetch_assoc();
    
    if(POST('submit'))
    {
      $pass = POST('newname');
      if(!$pass)
        $error[] = lang('empty_pass');
      elseif(mb_strlen($pass) < 4 or mb_strlen($pass) > 15)
      {
        $error[] = lang('pass_wrong_len');
        $pass = '';  
      }
      
      $pass_rep = POST('name_rep');
      if($pass != $pass_rep)
      {
        $error[] = lang('miss_pass');
        $pass = '';
        $pass_rep = ''; 
      }
        
      if(!$error)
      {
        core::$db->query('UPDATE `ds_users` SET `password`="'.core::$db->res(md5(md5($pass))).'" WHERE `id` = "'.$data['userid'].'" LIMIT 1;');
        core::$db->query('DELETE FROM `ds_recpassword` WHERE `id` = "'.$data['id'].'";');
        func::notify(lang('rec_pass'), lang('reg_succ'), core::$home.'/login');  
      }
    }
      
    engine_head(lang('rec_pass'));
    temp::HTMassign('error', $error);
    temp::assign('num', $key);
    temp::display('user.newpass');
    engine_fin(); 
  }
}
else
  denied();  

