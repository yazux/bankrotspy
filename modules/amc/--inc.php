<?php

defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id) { 
    engine_head(lang('title'));
    temp::assign('title', lang('title'));
    
    temp::display('access.denied');
    engine_fin();
}