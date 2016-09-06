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

// Проверка на активность пользователя. Если не активен, то вылогиниваем его и перенаправляем на главную
if ( core::$user_id && (core::$user_active == 0) ) {
    core::user_unset();
    header( "Location: /" );
    exit();
} 

$query = 'SELECT COUNT(*) FROM `ds_users` WHERE `lastvisit` > "' . (time() - core::$set['onlinetime']) . '";';
$query .= 'SELECT COUNT(*) FROM `ds_guests` WHERE `lastdate` > "' . (time() - core::$set['onlinetime']) . '";';
$query .= 'SELECT * FROM `ds_menu` ORDER BY `sort`;';
if(core::$rights == 100)
  $query .= 'SELECT * FROM `ds_feedback_comm_rdm` WHERE `modid` = "1" AND `userid` = "'.core::$user_id.'";';

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
  if(!mb_substr_count($data['link'], '<<home_link>>'))
    $loc['new_tab'] = 1;
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

if(core::$rights == 100)
{
  core::$db->next_result();
  $res = core::$db->store_result();
  $dta = $res->fetch_array();
}

//Счетчики
//Техподержка

if (CAN('tech_support', 0)){
    counts::cnew('unread_support','SELECT COUNT(*) FROM `ds_support` WHERE `read` = "0" AND `closed` = "0" ;','/support');
} elseif(core::$user_id) {
    counts::cnew('unread_support','SELECT COUNT(*) FROM `ds_support` WHERE `read` = "1" AND `usread` = "0" AND `userid` = "'.core::$user_id.'" ;','/support');
}

//Новости
counts::cnew('unread_news' ,'SELECT COUNT(*) FROM `ds_news` LEFT JOIN `ds_news_rdm` ON `ds_news`.`id` = `ds_news_rdm`.`artid` AND `ds_news_rdm`.`userid` = "' . core::$user_id . '" WHERE `ds_news_rdm`.`userid` IS NULL AND `ds_news`.`time` > "'.(time() - (3 * 24 * 3600)).'";',  '/news');
//counts::cnew('unread_art_comms' ,'SELECT COUNT(*) FROM `ds_article` LEFT JOIN `ds_art_comm_rdm` ON `ds_article`.`id` = `ds_art_comm_rdm`.`modid` AND `ds_art_comm_rdm`.`userid` = "' . core::$user_id . '" WHERE (`ds_art_comm_rdm`.`userid` IS NULL OR `ds_art_comm_rdm`.`rdmtime` < `ds_article`.`comtime`) AND `ds_article`.`type` = "0" AND `ds_article`.`comtime` > "'.(time() - (3 * 24 * 3600)).'";', '/articles/newcomm');
//
if(core::$rights == 100)
  counts::cnew('unread_feedback','SELECT COUNT(*) FROM `ds_comm` WHERE `module`="feedback" AND `mid` = "1" AND  `time` > "'.$dta['rdmtime'].'" AND `time` > '.(time() - (3 * 24 * 3600)).';','/feedback');
counts::cnew('unread_articles' ,'SELECT COUNT(*) FROM `ds_article` LEFT JOIN `ds_art_rdm` ON `ds_article`.`id` = `ds_art_rdm`.`artid` AND `ds_art_rdm`.`userid` = "' . core::$user_id . '" WHERE `ds_art_rdm`.`userid` IS NULL AND `ds_article`.`type` = "0" AND `ds_article`.`time` > "'.(time() - (3 * 24 * 3600)).'";',  '/articles/new');
counts::cnew('unread_art_comms' ,'SELECT COUNT(*) FROM `ds_article` LEFT JOIN `ds_art_comm_rdm` ON `ds_article`.`id` = `ds_art_comm_rdm`.`modid` AND `ds_art_comm_rdm`.`userid` = "' . core::$user_id . '" WHERE (`ds_art_comm_rdm`.`userid` IS NULL OR `ds_art_comm_rdm`.`rdmtime` < `ds_article`.`comtime`) AND `ds_article`.`type` = "0" AND `ds_article`.`comtime` > "'.(time() - (3 * 24 * 3600)).'";', '/articles/newcomm');

/*
if(CAN('stats_moderate', 0))
  counts::cnew('onmoder_articles' ,'SELECT COUNT(*) FROM `ds_article` WHERE `type` = "2";', '/articles/onmoder');
*/

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
