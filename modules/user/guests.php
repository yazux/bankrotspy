<?php
defined('DS_ENGINE') or die('web_demon laughs');


new nav; //Постраничная навигация
//nav::$kmess = 10;

$total = core::$db->query('SELECT COUNT(*) FROM `ds_guests` WHERE `lastdate` > "' . (time() - core::$set['onlinetime']) . '";')->count();
$res = core::$db->query('SELECT * FROM `ds_guests` WHERE `lastdate` > "' . (time() - core::$set['onlinetime']) . '" ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

$i = 0;
$arr = array();

if($total)
{
$eng_right = user::get_rights();
while($data = $res->fetch_assoc())
{
  $out = array();  
  
  $out['id'] =  $data['id'];
  $out['login'] = lang('guest');
  $out['usagent'] = $data['ua'];
  $out['avatar'] = '';
  
  $arr[] = $out;  
  $i++;  
}
}
engine_head(lang('u_online'));
temp::assign('total_in', $total);
temp::HTMassign('out', $arr);
temp::HTMassign('navigation', nav::display($total, core::$home.'/user/guests?'));

temp::display('user.guests');
engine_fin();