<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    $where .= " AND ("
        . "fio LIKE '%".$search."%'"
        . ")";
} 

$sortOrder = GET('sortOrder');
$sortField = GET('sortField');

if ( isset($sortOrder) && ($sortOrder == 'ASC') ) {
    $sortOrder = 'ASC';
} else {
    $sortOrder = 'DESC';
}

if ( isset($sortField) && ($sortField == 'fio') ) {
    $sortField = 'fio';
    $order = 'fio';    
} elseif (isset($sortField) && ($sortField == 'kolvo')) {
    $sortField = 'kolvo';
    $order = 'kolvo';
} elseif (isset($sortField) && ($sortField == 'suma')) {
    $sortField = 'suma';
    $order = 'suma';   
} else {
    $sortField = 'id';
    $order = 'id';
}

$order .= ' ' . $sortOrder;

$cntQuery = 'SELECT COUNT(*) AS cnt FROM ds_winners WHERE ' . $where;

$query = "SELECT * FROM ds_winners"
        . ' WHERE ' . $where
        . ' ORDER BY ' . $order 
        . ' LIMIT ' . nav::$start . ', ' . nav::$kmess;

$cntQuery = core::$db->query($cntQuery);
$total = $cntQuery->fetch_assoc()['cnt'];
$res = core::$db->query( $query);

$data = [];
$i = 0;

while($row = $res->fetch_assoc()) {
    $data[$i]['id'] = $row['id'];
    $data[$i]['fio'] = $row['fio'];
    $data[$i]['kolvo'] = ($access === true) ? $row['kolvo'] : $access;
    $data[$i]['suma'] = ($access === true) ? $row['suma'] : $access;
    $i++;
}

$textQuery = core::$db->query('SELECT * FROM ds_pages WHERE id = "15" LIMIT 1;');
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
temp::assign('access', $access);

temp::HTMassign('navigation', nav::display($total, core::$home.'/winners/?', '', '', array('search'=>$search,'sortOrder'=>$sortOrder,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('winners.index');

engine_fin();
