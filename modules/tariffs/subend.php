<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!CAN('paycontent', 0))
{

  engine_head(lang('tariffs'));
  temp::display('tariffs.subend');
  engine_fin();

}
else
  denied();