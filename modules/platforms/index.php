<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    if (is_numeric($search) ) {
        $where .= " AND (`ds_maindata_platforms`.`id` LIKE '%".$search."%') ";
    } else {
        $where .= " AND ("
                . "`ds_maindata_platforms`.`id`  LIKE '%".$search."%' OR "
                . "`ds_maindata_platforms`.`platform_url` LIKE '%".$search."%'"
                . ")";
    }
} 

$sortField = GET('sortField');

$sortField = 'platform_url';
$order = '`ds_maindata_platforms`.`id`';    

$cntQuery = 'SELECT COUNT(*) AS cnt FROM `ds_maindata_platforms` WHERE ' . $where;
$query = 'SELECT * FROM `ds_maindata_platforms` '
        . ' WHERE ' . $where
        . ' ORDER BY ' . $order 
        . ' LIMIT ' . nav::$start . ', ' . nav::$kmess;

//var_dump($cntQuery);die();

$cntQuery = core::$db->query($cntQuery);
$total = $cntQuery->fetch_assoc()['cnt'];
$res = core::$db->query( $query);

$data = [];
$i = 0;

while($row = $res->fetch_assoc()) {

    $data[$i]['id'] = $row['id'];
    $data[$i]['platform_url'] = $row['platform_url'];
    $i++;
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "13" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();
engine_head($textData['name'], $textData['keywords'], $textData['description']);
temp::assign('title', $textData['name']);

$start = nav::$start;
$finish = $start + nav::$kmess;
if ($total < $finish ) {
    $finish = $total;
}

temp::assign('start', $start);
temp::assign('end', $finish);
temp::assign('total', $total);

temp::HTMassign('textData', text::out($textData['text'], 0));
temp::assign('sortField', $sortField);
temp::assign('search', $search);

temp::HTMassign('navigation', nav::display($total, core::$home.'/platforms/?', '', '', array('search'=>$search,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('platforms.index');

engine_fin();
