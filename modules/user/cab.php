<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id)
{
  engine_head(lang('cab'));
  temp::assign('user_ip', core::$ip);
  temp::assign('user_ua', core::$ua);

  temp::display('user.cab');
  engine_fin();   
}
else
  denied();



