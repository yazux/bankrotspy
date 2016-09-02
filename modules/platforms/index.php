<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    $where .= " AND ("
            . "`reestr_pl`.`ovner`  LIKE '%".$search."%' OR "
            . "`reestr_pl`.`nazva` LIKE '%".$search."%'"
            . ")";
} 

$sortOrder = GET('sortOrder');
$sortField = GET('sortField');

if ( isset($sortOrder) && ($sortOrder == 'DESC') ) {
    $sortOrder = 'DESC';
} else {
    $sortOrder = 'ASC';
}

if ( isset($sortField) && ($sortField == 'name') ) {
    $order = '`reestr_pl`.`nazva`';    
} else {
    $order = '`reestr_pl`.`id`';
}

$order .= ' ' . $sortOrder;

$cntQuery = 'SELECT COUNT(*) AS cnt FROM `reestr_pl` WHERE ' . $where;
$query = 'SELECT * FROM `reestr_pl` '
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
    $data[$i]['nazva'] = $row['nazva'];
    $data[$i]['link'] = $row['link'];
    $data[$i]['ovner'] = $row['ovner'];
    $data[$i]['koment'] = $row['koment'];
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
temp::assign('sortOrder', $sortOrder);
temp::assign('sortField', $sortField);
temp::assign('search', $search);

temp::HTMassign('navigation', nav::display($total, core::$home.'/platforms/?', '', '', array('search'=>$search,'sortOrder'=>$sortOrder,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('platforms.index');

engine_fin();
