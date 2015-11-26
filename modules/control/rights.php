<?php
defined('DS_ENGINE') or die('web_demon laughs');
$error = array();

$eng_right = user::get_rights(0);
$names_r = core::parse_lang('data/lang_rights/descr.lang');

$res = core::$db->query('SELECT * FROM `ds_rights` ORDER BY `id` DESC;');
$rmenu = array();

while($data = $res->fetch_array())
{
    $loc = array();
    $loc['id'] = $data['id'];
    $loc['name'] = $eng_right[$data['id']];
    $descr = unserialize($data['rights']);
    $all_desk = array();
  
    foreach ($descr['common'] AS $key=>$value) {
        $all_desk[$key] = $names_r[$key];  
    }
  
    foreach ($descr['paid'] AS $key=>$value) {
        $all_desk[$key] = $names_r[$key];  
    }
  
    //$loc['undtext'] = mb_substr(implode(', ', $all_desk),0,200).'...';
    $loc['undtext'] = implode(', ', $all_desk);
    $rmenu[] = $loc;  
}

engine_head(lang('admin_control'));
temp::HTMassign('error', $error);
temp::HTMassign('rmenu',$rmenu);  
temp::display('control.rights');
engine_fin();