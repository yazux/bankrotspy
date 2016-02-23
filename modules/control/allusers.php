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

if(isset($_GET['search']) && !empty($_GET['search'])) {
    // Обрабатываем поисковую фразу
    $search = core::$db->res($_GET['search']);
    // Формируем условие согласно поисковому запросу
    $where .= ' AND login LIKE "%'.$search.'%" OR mail LIKE "%'.$search.'%"';
}

// Выбираем пользователей по запросу
$cntQuery = 'SELECT COUNT(*) FROM `ds_users` WHERE ' . $where;
$query = 'SELECT * FROM `ds_users` WHERE ' . $where;

$total = core::$db->query( $cntQuery )->count();
$res = core::$db->query( $query. ' ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

while ($data = $res->fetch_assoc()) {
    $out = array();
    $out['i'] = $i;
    $out['id'] = $data['id'];
    $out['login'] = $data['login'];
    $out['rights'] = $eng_right[$data['rights']];
    $out['sex'] = $data['sex'];
    $out['online'] = user::is_online($data['lastvisit']);
    $out['avatar'] = user::get_avatar($data['id'], $data['avtime'], 1);
    $out['registered'] = date('d.m.Y', $data['time']);
    $out['tariffs']    = $tariffs;

    $arr[] = $out;

    $i++;
}

engine_head(lang('u_online'));
temp::assign('total_in', $total);
temp::assign('search', $search);
temp::HTMassign('out', $arr);
temp::HTMassign('navigation', nav::display($total, core::$home.'/control/allusers?', '', '', array('search'=>$search)));

temp::display('control.allusers');
engine_fin();