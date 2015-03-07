<?php
defined('DS_ENGINE') or die('web_demon laughs');

temp::GLBassign('sql_count',core::$db->req_count());
temp::HTMassign('counters',core::$set['counters']);  
temp::HTMassign('botreklam', '');
temp::display('core.fin');