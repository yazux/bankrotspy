<?php
defined('DS_ENGINE') or die('web_demon laughs');

$region = (int)$_GET['region'];
$addRegion = (int)$_GET['addRegion'];

if ( isset($region) && ($region!=0) && isset($addRegion) && ($addRegion!=0) ) {
    core::$db->query('UPDATE `ds_maindata_regions_add` SET `region_id`='.$region.' WHERE `id`='.$addRegion);
    header('Location: /control/regions');
} 

$res = core::$db->query('SELECT * FROM `ds_maindata_regions_add` WHERE region_id=0;');
$addRegions = array();
while( $data = $res->fetch_array() ) {
    $loc = array();
    $loc['id'] = $data['id'];
    $loc['name'] = $data['name'];
    $loc['number'] = $data['number'];
    $addRegions[$data['id']] = $loc;  
}

$res = core::$db->query('SELECT * FROM `ds_maindata_regions` WHERE 1;');

$regions = array();
while( $data = $res->fetch_array() ) {
    $loc = array();
    $loc['id'] = $data['id'];
    $loc['name'] = $data['name'];
    $loc['number'] = $data['number'];
    $regions[$data['id']] = $loc;  
}

engine_head(lang('admin_control'));
temp::HTMassign('addRegions',$addRegions);
temp::HTMassign('regions',$regions);
temp::display('control.regions');
engine_fin();