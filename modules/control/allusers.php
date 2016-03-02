<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$i = 0;
$total = 0;
$where = "1";
$arr = array();

$eng_right = user::get_rights();

// Выбираем тарифы
$query = core::$db->query('SELECT * FROM `ds_tariffs`');
while ($item = $query->fetch_assoc()) {
    $tariffs[] = $item;
}

$rights = isset($_GET['rights']) ? (int)$_GET['rights'] : -2;
if ( ($rights == -1) || ($rights == 0) || ($rights == 10) || ($rights == 11) || ($rights == 20) || ($rights == 70) || ($rights == 90) || ($rights == 100)) {
    $where .= " AND `rights`=" . $rights;
}

// Выбираем права
$res = core::$db->query('SELECT * FROM `ds_rights` ORDER BY `id` DESC;');

if(isset($_GET['search']) && !empty($_GET['search'])) {
    // Обрабатываем поисковую фразу
    $search = core::$db->res($_GET['search']);
    // Формируем условие согласно поисковому запросу
    $where .= ' AND (login LIKE "%'.$search.'%" OR mail LIKE "%'.$search.'%")';
}

// Выбираем пользователей по запросу
$cntQuery = 'SELECT COUNT(*) FROM `ds_users` WHERE ' . $where;
$query = 'SELECT * FROM `ds_users` WHERE ' . $where;
//var_dump($query);
$total = core::$db->query( $cntQuery )->count();
$res = core::$db->query( $query. ' ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

while ($data = $res->fetch_assoc()) {
    $out = array();
    $out['i']           = $i;
    $out['id']          = $data['id'];
    $out['login']       = $data['login'];
    $out['rights']      = $data['rights'];
    $out['rightsName']   = $eng_right[$data['rights']];
    $out['sex']         = $data['sex'];
    $out['online']      = user::is_online($data['lastvisit']);
    $out['avatar']      = user::get_avatar($data['id'], $data['avtime'], 1);
    $out['registered']  = date('d.m.Y', $data['time']);
    $out['tariffs']     = $tariffs;

    $arr[] = $out;

    $i++;
}

engine_head(lang('u_online'));
temp::assign('total_in', $total);
temp::assign('search', $search);
temp::assign('currentRights', $rights);
temp::HTMassign('rights', $eng_right);
temp::HTMassign('out', $arr);
temp::HTMassign('navigation', nav::display($total, core::$home.'/control/allusers?', '', '', array('search'=>$search,'rights'=>$rights)));

temp::display('control.allusers');
engine_fin();