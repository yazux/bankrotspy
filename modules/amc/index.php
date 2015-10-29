<?php

defined('DS_ENGINE') or die('web_demon laughs');

$search = GET('search');

$sql_calc = 'SQL_CALC_FOUND_ROWS';


// поиск
if (!empty($search) && strlen($search) >= 4 ) {
    $s = core::$db->res(GET('search'));
    
    $query = core::$db->query('SELECT ' . $sql_calc . ' * FROM `ds_maindata_organizers` WHERE 
                                `org_name` LIKE "%'.$s.'%" OR 
                                `contact_person` LIKE "%'.$s.'%" OR 
                                `inn` = "'.$s.'" OR
                                `phone` LIKE "%'.$s.'%" OR 
                                `mail` = "'.$s.'" ORDER BY bal DESC');
    $total = $query->num_rows;
    
} else {

    $id = intval(GET('id'));

    $page = !empty($_GET['page']) ? intval($_GET['page']) : 1; //current page
    $limit = 50;//perpage
    $offset = !empty($limit) ? ($limit * ($page - 1)) : 0;
    
    $query = core::$db->query('SELECT ' . $sql_calc . ' * FROM `ds_maindata_organizers` ORDER BY bal DESC LIMIT '.$limit.' OFFSET ' . core::$db->res($offset));
    $_SESSION['hash'] = md5($query->num_rows);
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "9" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();

$data = array();
$i = 0;
while($row = $query->fetch_assoc()) {
    
    $data[$i]['id'] = $row['id'];
    $data[$i]['name'] = str_replace(array('ИП ', 'ИП'), '', $row['org_name']);
    $data[$i]['phone'] = intval($row['phone']);
    if($row['totaldoc'] > 0) {
        $data[$i]['rating'] = $row['bal'];
    } else {
        $data[$i]['rating'] = 'Нет данных';
    }
    $data[$i]['totaldoc'] = $row['totaldoc'];
    $data[$i]['linkdocs'] = $row['linkdocs'];
    $data[$i]['fasdocs'] = $row['fasdocs'];
    $data[$i]['email'] = $row['mail'];
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

engine_head(lang('title'));
temp::assign('title', lang('title'));

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


temp::HTMassign('textData', $textData['text']);
temp::assign('start', $start);
temp::assign('end', $end);
temp::assign('total', $total);

temp::HTMassign('pagination', $pagination);
temp::HTMassign('data', $data);

if ($query->num_rows > 0) {
    temp::display('amc.view');
} else {
    temp::display('amc.notfound');
}
engine_fin();
