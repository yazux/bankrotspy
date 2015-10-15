<?php

defined('DS_ENGINE') or die('web_demon laughs');

$id = GET('id');


$query = core::$db->query('SELECT * FROM `ds_maindata_organizers` WHERE `id` = "'.core::$db->res($id).'"');

$chartQuery = core::$db->query('SELECT * FROM `ds_ky_bals` WHERE `id` = "'.core::$db->res($id).'"');  


    
$data = array();

$data = $query->fetch_assoc();

if ($chartQuery->num_rows) {
    $i = 0;
    while ($row = $chartQuery->fetch_assoc()) {
        var_dump($row);
        $data['chart'][$i]['date'] = date("Y-m-d",$row['tm']);
        $data['chart'][$i]['value'] = $row['bals'];
        $i++;
    }
}

engine_head(lang('title'));

temp::assign('title', lang('title'));
temp::HTMassign('data', $data);
temp::display('amc.view');
engine_fin();
