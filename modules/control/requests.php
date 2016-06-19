<?php
defined('DS_ENGINE') or die('web_demon laughs');

// Начальное условие для вывода транзакций 
$where = "1";

// Перехватываем дату
//$date = $_GET['date'];
//if ( !$date ) {
//    $date = date("Y-m-d");
//}
//$where .= " AND EXTRACT(YEAR_MONTH FROM FROM_UNIXTIME(paytime))=EXTRACT(YEAR_MONTH FROM '$date')";

$companyId = (int)GET('companyId');
$companyEmail = GET('email');

//var_dump($companyEmail);
//var_dump($_POST);
//die('sss');
if ( isset($companyId) && $companyId > 0 && isset($companyEmail) ) {
    $companyStatus = GET('status');
    if ( isset($companyStatus) && ($companyStatus==1) ) {
        $companyStatus = 1;
    } else {
        $companyStatus = 0;
    }
    core::$db->query("UPDATE `companies` SET `email`='".core::$db->res($companyEmail)."', status=".core::$db->res($companyStatus)." WHERE id=" . $companyId);
}

// Перехватываем поисковый запрос
if(isset($_GET['search']) && !empty(trim(strip_tags($_GET['search'])))) {
    // Обрабатываем поисковую фразу
    $search = core::$db->res(trim(strip_tags($_GET['search'])));
    // Формируем условие согласно поисковому запросу
    $where .= ' AND (CONCAT(`ds_request`.`name`, " ", `ds_request`.`phone`, " ", `ds_request`.`email`, " ", `ds_request`.`city`) LIKE "%'.$search.'%")';
}

// Постраничная навигация. 20 на странице.
//new nav(20);

// Подсует и выборка заявок
$total = core::$db->query('SELECT COUNT(*) FROM `ds_request` WHERE ' . $where . ';')->count();
$res = core::$db->query(
        'SELECT `ds_request`.*, `companies`.`name` AS cname, `companies`.`email` AS cemail '
        . 'FROM `ds_request` '
        . 'LEFT JOIN `companies` ON `companies`.`id` = `ds_request`.`company_id` '
        . 'WHERE ' . $where . ' '
        . 'ORDER BY `created` DESC'
    );

$i = 0;
$arr = array();
$month_names = array(
    1 => 'Январь',
    2 => 'Февраль',
    3 => 'Март',
    4=> 'Апрель',
    5=> 'Май',
    6=> 'Июнь',
    7=> 'Июль',
    8=> 'Август',
    9=> 'Сентябрь',
    10=> 'Октябрь',
    11=> 'Ноябрь',
    12=> 'Декабрь'
);

//$month_summ = array();

// Перебор всех полученных транзакций и установка доплнительных значений
while($data = $res->fetch_assoc()) {
    $out = array();

    $out['id'] =  $data['id'];
    $out['companyId'] = $data['company_id'];
    $out['username'] = $data['name'];
    $out['phone'] =  $data['phone'];
    $out['email'] =  $data['email'];
    $out['city'] =  $data['city'];
    $out['inn'] =  $data['inn'];
    $out['lotid'] =  $data['lotid'];
    $out['cname'] =  $data['cname'];
    $out['cemail'] =  $data['cemail'];
    $out['created'] =  ds_time($data['created'], '%d %B2 %Y');

    $month = date('n', $data['created']);
    $year = date('Y', $data['created']);

    $arr[$year][$month][] = $out;
    $i++;
//    var_dump($out);
}

$companies = array();
$resQuery = core::$db->query('SELECT * FROM `companies` WHERE 1');
while ( $company =  $resQuery->fetch_assoc() ) {
    $companies[$company['id']] = $company;
}

// Выдем переменные в шаблон
engine_head(lang('u_online'));
temp::assign('search', $search);
temp::HTMassign('out', $arr);
temp::HTMassign('marr', $month_names);
temp::HTMassign('companies', $companies);
temp::assign('uall', $total);
temp::HTMassign('navigation', nav::display($total, core::$home.'/control/requests?', '', '', array('search'=>$search)));

temp::display('control.requests');
engine_fin();