<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id and POST('act'))
{
  $user_name = core::$user_name;  
  core::user_unset();
  func::notify(lang('exit'), lang('bye').' '.$user_name, core::$home, lang('home_back'));  
}
else
 denied();


  