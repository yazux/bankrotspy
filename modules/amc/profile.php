<?php

defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(GET('id'));

$query = core::$db->query('SELECT * FROM `ds_maindata_organizers` WHERE `id` = "'.core::$db->res($id).'"');
$chartQuery = core::$db->query('SELECT * FROM `ds_ky_bals` WHERE `id` = "'.core::$db->res($id).'"');  
    
$data = array();

$data = $query->fetch_assoc();

if ($chartQuery->num_rows) {
    $i = 0;
    while ($row = $chartQuery->fetch_assoc()) {
        
        $data['chart'][$i]['date'] = date("Y-m-d",$row['tm']);
        $data['chart'][$i]['value'] = $row['bals'];
        $i++;
    }
}

if ($data['totaldoc'] > 0 && $data['totaldoc'] < 3) {
    $rating = 'Мало данных';
} elseif($data['totaldoc'] == 0) {
    $rating = 'Нет данных';
} elseif ($data['bal'] > 5 ) {
    $rating = '<span class="plus">' . $data['bal'] . '</span>';
} else {
    $rating = '<span class="minus">' . $data['bal'] . '</span>';
}

if (!empty($data['totaldoc'])) {
    $linkdocs = '<a href="' . $data['linkdocs'] . '" target="_blank">Смотреть</a>';
} else {
    $linkdocs = 'Нет данных';
}
    
if(!empty($data['fasdocs'])) {
    $fasdocs = '<a href="' . $data['fasdocs'] .'" target="_blank">Смотреть</a>';
} else {
    $fasdocs = 'Нет данных';
}

if(!empty($data['phone'])) {
    $phone = $data['phone'];
} else {
     $phone = 'Нет';
}

if(!empty($data['arbitr_profile'])) {
    $arbitr_profile = '<a href="' . $data['arbitr_profile'] . '" target="_blank">Смотреть</a>';
} elseif (!empty($data['org_profile'])) {
    $arbitr_profile = '<a href="' . $data['arbitr_profile'] . '" target="_blank">Смотреть</a>';
} else {
    $arbitr_profile = 'Нет';
}

$data['contact_person'] = ($access === true) ? $data['contact_person'] : $access;
$data['phone'] = ($access === true) ? $phone : $access;
$data['inn'] = ($access === true) ? $data['inn'] : $access;
$data['linkdocs'] = ($access === true) ? $linkdocs : $access;
$data['fasdocs'] = ($access === true) ? $fasdocs : $access;

$data['rating'] = ($access === true) ? $rating : $access;
$data['arbitr_profile'] = ($access === true) ? $arbitr_profile : $access;


engine_head(lang('title'));

temp::assign('title', lang('title'));
temp::HTMassign('data', $data);
temp::display('amc.profile');
engine_fin();
