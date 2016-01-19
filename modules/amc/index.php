<?php

defined('DS_ENGINE') or die('web_demon laughs');

$search = GET('search');

$sql_calc = 'SQL_CALC_FOUND_ROWS ';


// поиск
if (!empty($search) && strlen($search) >= 4 && $access === true) {
    $s = core::$db->res(GET('search'));
    
    $select = '* ';
    $order = '`bal` DESC';
    if(is_numeric($s)) {
        $where = '`inn` = "'.$s.'" OR phone = "'.$s.'"';
    } elseif (filter_var($s, FILTER_VALIDATE_EMAIL)) {
        $where = 'mail = "'.$s.'"';
    } else {
        $select = '*, MATCH (`ds_maindata_organizers`.`org_name`, `ds_maindata_organizers`.`contact_person`, `ds_maindata_organizers`.`manager`) AGAINST ("»' . $s . '»" IN BOOLEAN MODE) as `rel` ';
        $where = ' MATCH (`ds_maindata_organizers`.`org_name`, `ds_maindata_organizers`.`contact_person`, `ds_maindata_organizers`.`manager`) AGAINST ("»' . $s . '»" IN BOOLEAN MODE) ';
        $order = '`rel` DESC, `bal` DESC';
    }
    
    $query = core::$db->query('SELECT ' . $sql_calc . $select .' FROM `ds_maindata_organizers` WHERE 
                                '.$where.'
                                ORDER BY ' . $order);
    $total = $query->num_rows;
    
    
} else {

    $id = intval(GET('id'));

    $page = !empty($_GET['page']) ? intval($_GET['page']) : 1; //current page
    $limit = 50;//perpage
    $offset = !empty($limit) ? ($limit * ($page - 1)) : 0;
    
    $query = core::$db->query('SELECT ' . $sql_calc . ' * FROM `ds_maindata_organizers` ORDER BY bal DESC LIMIT '.$limit.' OFFSET ' . core::$db->res($offset));
    $total = $query->num_rows;
}

$data = [];
$i = 0;

while($row = $query->fetch_assoc()) {
    
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


if (empty($total)) {
    $queryCalc = core::$db->query('SELECT FOUND_ROWS () AS total');
    $p = $queryCalc->fetch_assoc();
    $total = $p['total'];
}

if (empty($search)) {
    $pagination = new pagination($page, $total, $limit);
    $pagination = $pagination->createLinks();
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "9" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();

engine_head($textData['name'], $textData['keywords'], $textData['description']);

temp::assign('title', $textData['name']);

if(!empty($search)) {

    temp::assign('search', $total);
} else {
    if ($offset == 0) {
        $start = 1;
    } else {
        $start = $offset;
    }
    $end = $offset + 50;
    
    if($end > $total) {
        $end = $start + $query->num_rows;
    }
}





temp::HTMassign('textData', text::out($textData['text'], 0));
temp::assign('start', $start);
temp::assign('end', $end);
temp::assign('total', $total);

temp::assign('access', $access);

temp::HTMassign('pagination', $pagination);
temp::HTMassign('data', $data);

if ($query->num_rows > 0) {
    temp::display('amc.view');
} else {
    temp::display('amc.notfound');
}
engine_fin();
