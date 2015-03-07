<?php  
defined('DS_ENGINE') or die('web_demon laughs');

$out = GET('out');
$url = parse_url($out);
$res = core::$db->query('SELECT * FROM `ds_forbidden_mails` WHERE `domain` = "'.core::$db->res($url['host']).'";');

if(!$res->num_rows)
{
  header('Location: ' . htmlentities($out, ENT_QUOTES, 'UTF-8'));
  exit();
}
else
{
   engine_head(lang('bad_url')); 
    
   temp::assign('go_url', $out);
   temp::assign('back_url', $_SERVER['HTTP_REFERER']); 
   temp::display('url.index');
   engine_fin(); 
}


