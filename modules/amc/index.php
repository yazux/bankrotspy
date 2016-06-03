<?php

defined('DS_ENGINE') or die('web_demon laughs');

new nav(50); 

$where = '1';

$search = trim(strip_tags(GET('search')));

if ( isset($search) && !empty($search)) {
    if (is_numeric($search) ) {
        $where .= " AND `ds_maindata_organizers`.`inn` LIKE '%".$search."%'";
    } else {
        $where .= " AND `ds_maindata_organizers`.`org_name` LIKE '%".$search."%'";
    }
} 

$sortOrder = GET('sortOrder');
$sortField = GET('sortField');

if ( isset($sortOrder) && ($sortOrder == 'DESC') ) {
    $sortOrder = 'DESC';
} else {
    $sortOrder = 'ASC';
}

if ( isset($sortField) && ($sortField == 'bal') ) {
    $sortField = 'bal';
    $order = '`ds_maindata_organizers`.`bal`';
} else {
    $sortField = 'name';
    $order = '`ds_maindata_organizers`.`org_name`';
}

$order .= ' ' . $sortOrder . ', `ds_maindata_organizers`.`totaldoc` DESC';

$cntQuery = 'SELECT COUNT(*) AS cnt FROM `ds_maindata_organizers` WHERE ' . $where;
$query = 'SELECT * FROM `ds_maindata_organizers` '
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

    if ($row['totaldoc'] > 0 && $row['totaldoc'] < 3) {
        $rating = 'Мало данных';
    } elseif($row['totaldoc'] == 0) {
        $rating = 'Нет данных';
    } elseif ($row['bal'] > 5 ) {
        $rating = '<a class="plus" href="' . core::$home . '/amc/' . $row['id'] . '" target="_blank">' . $row['bal'] . '</a>';
    } else {
        $rating = '<a class="minus" href="' . core::$home . '/amc/' . $row['id'] . '" target="_blank">' . $row['bal'] . '</a>';
    }
    
    if (!empty($row['totaldoc'])) {
        $linkdocs = '<a class="namelink" href="' . $row['linkdocs'] . '" target="_blank">Смотреть</a>';
    } else {
        $linkdocs = 'Нет данных';
    }
    
    if(!empty($row['fasdocs'])) {
        $fasdocs = '<a class="namelink" href="' . $row['fasdocs'] .'" target="_blank">Смотреть</a>';
    } else {
        $fasdocs = 'Нет данных';
    }
 
    $data[$i]['id'] = $row['id'];
    $data[$i]['name'] = str_replace(['ИП ', 'ИП'], '', $row['org_name']);
    $data[$i]['phone'] = ($access === true) ? intval($row['phone']) : $access;
    $data[$i]['rating'] = ($access === true) ? $rating : $access;
    $data[$i]['fasdocs'] = ($access === true) ? $fasdocs : $access;
    $data[$i]['linkdocs'] = ($access === true) ? $linkdocs : $access;
    
    $data[$i]['totaldoc'] = ($access === true)? $row['totaldoc'] : $access;

    $data[$i]['email'] = ($access === true) ? $row['mail'] : $access;

    $i++;
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "9" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();
engine_head($textData['name'], $textData['keywords'], $textData['description']);
temp::assign('title', $textData['name']);

$start = nav::$start;
$finish = $start + nav::$kmess;
temp::assign('start', $start);
temp::assign('end', $finish);
temp::assign('total', $total);

temp::HTMassign('textData', text::out($textData['text'], 0));
temp::assign('sortOrder', $sortOrder);
temp::assign('sortField', $sortField);
temp::assign('search', $search);
temp::assign('access', $access);

temp::HTMassign('navigation', nav::display($total, core::$home.'/amc/?', '', '', array('search'=>$search,'sortOrder'=>$sortOrder,'sortField'=>$sortField)));
temp::HTMassign('data', $data);

temp::display('amc.view');

engine_fin();
