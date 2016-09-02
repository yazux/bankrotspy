<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    if (is_numeric($search) ) {
        $where .= " AND (`ds_maindata_debtors`.`inn` LIKE '%".$search."%') ";
    } else {
        $where .= " AND ("
                . "`ds_maindata_debtors`.`dept_name`  LIKE '%".$search."%' OR "
                . "`ds_maindata_debtors`.`inn` LIKE '%".$search."%'"
                . ")";
    }
}

$sortOrder = GET('sortOrder');
$sortField = 'name';

if ( isset($sortOrder) && ($sortOrder == 'DESC') ) {
    $sortOrder = 'DESC';
} else {
    $sortOrder = 'ASC';
}

$order = '`ds_maindata_debtors`.`dept_name`';    

$order .= ' ' . $sortOrder;

$cntQuery = 'SELECT COUNT(*) AS cnt FROM `ds_maindata_debtors` WHERE ' . $where;
$query = 'SELECT * FROM `ds_maindata_debtors` '
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
    $data[$i]['dept_name'] = str_replace(['ИП ', 'ИП'], '', $row['dept_name']);
    $data[$i]['inn'] = $row['inn'];
    $data[$i]['debt_profile'] = $row['debt_profile'];
    $i++;
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "12" LIMIT 1;');
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

temp::HTMassign('navigation', nav::display($total, core::$home.'/debtors/?', '', '', array('search'=>$search,'sortOrder'=>$sortOrder,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('debtors.index');

engine_fin();
