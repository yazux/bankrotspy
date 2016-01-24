<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$arr = array();
$eng_right = user::get_rights();

if(isset($_GET['search']) && !empty($_GET['search'])) {
    
    $search = core::$db->res($_GET['search']);
    
    if (filter_var($search, FILTER_VALIDATE_EMAIL)) {
        $where = ' mail LIKE "%'.$search.'%"';
    } elseif (intval($search) > 0) {
        $where = ' id = "'.$search.'"';
    } else {
        $where = ' login = "'.$search.'"';
    }
    
    $query = core::$db->query('SELECT * FROM `ds_tariffs`');
    
    while ($item = $query->fetch_assoc()) {
        $tariffs[] = $item;
    }
    
    $res = core::$db->query('SELECT * FROM `ds_users` WHERE ' . $where);
    if($res->num_rows > 0 ) {
        
        $user = $res->fetch_array();

        $user['id'] =       $user['id'];
        $user['login'] =    $user['login'];
        $user['sex'] =      $user['sex'];
        $user['rights'] =   $eng_right[$user['rights']];
        $user['online'] =   user::is_online($user['lastvisit']);
        $user['avatar'] =   user::get_avatar($user['id'], $user['avtime'], 1);
        $user['tariffs'] = $tariffs;
        $arr[] = $user;
    }
} else {
    
    $total = core::$db->query('SELECT COUNT(*) FROM `ds_users` ;')->count();
    $res = core::$db->query('SELECT * FROM `ds_users` ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

    $i = 0;
   
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
        $arr[] = $out;
        $i++;
    }
}


engine_head(lang('u_online'));
temp::assign('total_in', $total);
temp::HTMassign('out', $arr);
temp::HTMassign('navigation', nav::display($total, core::$home.'/control/allusers?'));

temp::display('control.allusers');
engine_fin();