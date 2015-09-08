<?php

defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

if(POST('submit')) {
    
    $baze_yield = POST('baze_yield');
      
    if(!preg_match('#^([0-9]+)(-([0-9]+))?$#', $baze_yield)) {
        $error[] = lang('no_baze_yield');
    }
    /*
    if(!$baze_yield) {
        $error[] = lang('no_baze_yield');
    }*/
    
    if(!$error) {
        $query = 'UPDATE `ds_settings` SET `val` = "' . core::$db->res($baze_yield) . '" WHERE `key` = "baze_yield";';

        core::$db->multi_query($query);
        core::$db->multi_free();

        func::notify(lang('pnews'), lang('settings_saved'), core::$home . '/control/pnews', lang('continue'));
    }
}

engine_head(lang('pnews'));
temp::HTMassign('error', $error);
temp::assign('baze_yield', core::$set['baze_yield']);
temp::display('control.pnews');
engine_fin();