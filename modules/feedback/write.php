<?php
if(core::$user_id and CAN('create_comm', 0))
{
  engine_head(lang('feed'));
  if(core::$user_id and CAN('create_comm', 0))
      temp::assign('its_user',core::$user_id);
  temp::display('feedback.write');
  engine_fin();
}
else
{
   uscache::rem('mess_head', lang('error_head'));
   uscache::rem('mess_body', lang('error_body'));
        
   header('Location: ' . core::$home.'/feedback');
   exit();
}