<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    if (is_numeric($search) ) {
        $where .= " AND (`reestr_debtor`.`inn` LIKE '%".$search."%') ";
    } else {
        $where .= " AND ("
                . "`reestr_debtor`.`fio`  LIKE '%".$search."%' OR "
                . "`reestr_debtor`.`inn` LIKE '%".$search."%'"
                . ")";
    }
}

$sortOrder = GET('sortOrder');
$sortField = GET('sortField');

if ( isset($sortOrder) && ($sortOrder == 'DESC') ) {
    $sortOrder = 'DESC';
} else {
    $sortOrder = 'ASC';
}

if ( isset($sortField) && ($sortField == 'name') ) {
    $order = '`reestr_debtor`.`fio`';    
} else {
    $order = '`reestr_debtor`.`id`';
}

$order .= ' ' . $sortOrder;

$cntQuery = 'SELECT COUNT(*) AS cnt FROM `reestr_debtor` WHERE ' . $where;
$query = 'SELECT * FROM `reestr_debtor` '
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
    $data[$i]['fio'] = str_replace(['ИП ', 'ИП'], '', $row['fio']);
    $data[$i]['inn'] = $row['inn'];
    $data[$i]['region'] = $row['region'];
    $data[$i]['adres'] = $row['adres'];
    $data[$i]['link'] = $row['link'];
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

temp::HTMassign('navigation', nav::display($total, core::$home.'/debtors/?', '', '', array('search'=>$search,'sortOrder'=>$sortOrder,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('debtors.index');

engine_fin();
