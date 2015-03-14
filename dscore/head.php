<?php
defined('DS_ENGINE') or die('web_demon laughs');

if ((stristr(core::$ua, 'msie') && stristr(core::$ua, 'windows')))     //or stristr(core::$ua, "chrome")
{
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Content-type: text/html; charset=UTF-8'); 
}
else
{
    header("Cache-Control: public");
    header('Content-type: text/html; charset=UTF-8');
    //header('Content-type: application/xhtml+xml; charset=UTF-8');
}
header('Expires: ' . date('r',  time() + 60));


$query = 'SELECT COUNT(*) FROM `ds_users` WHERE `lastvisit` > "' . (time() - core::$set['onlinetime']) . '";';
$query .= 'SELECT COUNT(*) FROM `ds_guests` WHERE `lastdate` > "' . (time() - core::$set['onlinetime']) . '";';
$query .= 'SELECT * FROM `ds_menu` ORDER BY `sort`;';

core::$db->multi_query($query);

$result = core::$db->store_result()->fetch_row();
$users = $result[0];

core::$db->next_result();

$result = core::$db->store_result()->fetch_row();
$guests = $result[0];

core::$db->next_result();
$res = core::$db->store_result();
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['name'] = $data['name'];
  $loc['one_cnt'] = $data['one_counter'];
  $loc['two_cnt'] = $data['two_counter'];
  $loc['three_counter'] = $data['three_counter'];
  $loc['link'] = str_replace('<<home_link>>', core::$home, $data['link']);
  $rmenu[] = $loc;
}

temp::GLBassign('onl_all',$guests + $users);
temp::GLBassign('onl_guests',$guests);
temp::GLBassign('onl_users', $users);

if(core::$user_id)
{
  if(file_exists('images/avatars/'.core::$avtime.'_'.core::$user_id.'.png'))
    $avatar =  '/images/avatars/'.core::$avtime.'_'.core::$user_id.'.png';
  else
    $avatar = '';
  temp::assign('head_avatar', $avatar);
}

//Счетчики
counts::cnew('trash_query' ,'SELECT COUNT(*) FROM `ds_support`;',  ''); // Потом убрать. multi_query не воспринимает один запрос как таковой.
//Техподержка
if(CAN('tech_support', 0))
  counts::cnew('unread_support','SELECT COUNT(*) FROM `ds_support` WHERE `read` = "0" AND `closed` = "0"','/support');
elseif(core::$user_id)
  counts::cnew('unread_support','SELECT COUNT(*) FROM `ds_support` WHERE `read` = "1" AND `usread` = "0" AND `userid` = "'.core::$user_id.'"','/support');

//Смотрим, если ли сообщения
if(uscache::ex('mess_head'))
{
  temp::assign('usc_mess_head', uscache::get('mess_head'));
  temp::assign('usc_mess_body', uscache::get('mess_body'));
  uscache::del('mess_head');
  uscache::del('mess_body');
}

temp::GLBassign('themepath', core::$theme_path);
temp::GLBassign('new_mail', core::$new_mail);
temp::GLBassign('home',  core::$home);
temp::assign('title', core::$page_title);
temp::HTMassign('rmenu',  $rmenu);
if(core::$user_id)
  temp::GLBassign('user_id', core::$user_id);
if(core::$user_name)
  temp::GLBassign('user_name', core::$user_name);
if(core::$module != core::$set['module'] or core::$action != core::$set['action'])
  temp::GLBassign('noindex',1);
if(core::$page_keywords)
  temp::assign('keywords', core::$page_keywords);
elseif(core::$module=='index' AND core::$action=='index')
  temp::HTMassign('keywords', core::$set['keywords']);
if(core::$page_description)
  temp::assign('description', core::$page_description);
elseif(core::$module=='index' AND core::$action=='index')
  temp::HTMassign('description', core::$set['description']);
temp::HTMassign('topreklam',   '');
temp::display('core.head');

