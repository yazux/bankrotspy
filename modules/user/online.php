<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация
$total = core::$db->query('SELECT COUNT(*) FROM `ds_users` WHERE `lastvisit` > "' . (time() - core::$set['onlinetime']) . '";')->count();
$res = core::$db->query('SELECT * FROM `ds_users` WHERE `lastvisit` > "' . (time() - core::$set['onlinetime']) . '" ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

$i = 0;
$arr = array();
$eng_right = user::get_rights();
while($data = $res->fetch_assoc()) {
    $out = array();
    $out['i'] = $i;
    $out['id'] = $data['id'];
    $out['login'] = $data['login'];
  
    $out['rights'] = $eng_right[$data['rights']];
    $out['sex'] = $data['sex'];
    $out['online'] = user::is_online($data['lastvisit']);

    $out['avatar'] = user::get_avatar($data['id'], $data['avtime'], 1);
  
    $out['registered'] = date('d.m.Y', $data['time']);
    $arr[] = $out; 
    $i++;
}

engine_head(lang('u_online'));
temp::assign('total_in', $total);
temp::HTMassign('out', $arr);
temp::HTMassign('navigation', nav::display($total, core::$home.'/user/online?'));

temp::display('user.online');
engine_fin();