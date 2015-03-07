<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id)
{
  $error=array();  
  if(POST('act'))
  {
    $orig_pass = POST('orig_pass');  
    if(!$orig_pass)  
      $error[] = lang('miss_pass');
    elseif(mb_strlen($orig_pass) < 4 or mb_strlen($orig_pass) > 15)
      $error[] = lang('wrong_len_pass');
    
    $new_pass = POST('new_pass');
    $rep_pass = POST('rep_pass');
    if(!$new_pass)
      $error[] = lang('miss_pass_new');
    elseif(mb_strlen($new_pass) < 4 or mb_strlen($new_pass) > 15)
      $error[] = lang('wnew_len_pass');
    elseif($new_pass != $rep_pass)
      $error[] = lang('pass_wrong');
    elseif(md5(md5($orig_pass)) != core::$user_md_pass)
      $error[] = lang('pass_d_ex');
      
    if(!$error)
    {
      core::$db->query('UPDATE `ds_users` SET `password`="'.core::$db->res(md5(md5($new_pass))).'" WHERE `id` = "'.core::$user_id.'" LIMIT 1;');  
      
      if (isset($_COOKIE['cuser_id']) && isset($_COOKIE['cuser_pass']))
      {
        $_SESSION['user_pass'] = md5(md5($new_pass));  
        $cups = md5($new_pass);  
        setcookie('cuser_pass', $cups, time() + 3600 * 24 * 365);  
      }
      
      func::notify(lang('settings'), lang('pass_changed'), core::$home.'/user/cab', lang('continue'));  
    }   
  }    
    
  engine_head(lang('settings'));
  temp::HTMassign('error', $error);
  temp::display('user.chpass');
  engine_fin();   
}
else
  denied();



