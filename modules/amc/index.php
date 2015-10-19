<?php

defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(GET('id'));

$page = !empty($_GET['page']) ? intval($_GET['page']) : 1; //current page
$limit = 50;//perpage
$offset = !empty($limit) ? ($limit * ($page - 1)) : 0;
$total = !empty($_SESSION['total']) ? $_SESSION['total'] : 0;
    
$query = core::$db->query('SELECT SQL_CALC_FOUND_ROWS * FROM `ds_maindata_organizers` ORDER BY bal DESC LIMIT '.$limit.' OFFSET ' . core::$db->res($offset));

$data = array();
$i = 0;
while($row = $query->fetch_array()) {
    
    $data[$i]['id'] = $row['id'];
    $data[$i]['name'] = $row['org_name'];
    $data[$i]['phone'] = $row['phone'];
    $data[$i]['rating'] = $row['bal'];
    $data[$i]['email'] = $row['mail'];
    $i++;
}

if(empty($total)) {
    $query = core::$db->query('SELECT FOUND_ROWS () AS total');
    $p = $query->fetch_assoc();
    $total = $_SESSION['total'] = $p['total'];
}

$pagination = new pagination($page, $total, $limit);
$pagination = $pagination->createLinks();


engine_head(lang('title'));
temp::assign('title', lang('title'));
temp::HTMassign('pagination', $pagination);
temp::HTMassign('data', $data);
temp::display('amc.view');
engine_fin();
