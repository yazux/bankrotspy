<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(!core::$user_id)
  denied();
$error = array();

if(POST('submit'))
{
   $text = POST('mess');
   if(!$text) 
     $error[] = lang('no_text');
   
   
   if(!$error)
   {
      core::$db->query('INSERT INTO `ds_support` SET 
        `userid` = "'.core::$db->res(core::$user_id).'",
        `autor` = "'.core::$db->res(core::$user_name).'",
        `time` = "'.time().'",
        `newtime` = "'.time().'",
        `text` = "'.core::$db->res($text).'",
        `cache` = "'.core::$db->res(text::presave($text)).'";');
      
      $insert_id = core::$db->insert_id;
      
      uscache::rem('mess_head', lang('support'));
      uscache::rem('mess_body', lang('stat_added')); 
      header('Location: '.core::$home.'/support/view?id='.$insert_id);
      exit();
   } 
}  
  
engine_head(lang('support'));
temp::HTMassign('error', $error); 
temp::display('support.newticket');
engine_fin();