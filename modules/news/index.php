<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_news` ;')->count();

  $res = core::$db->query('SELECT `ds_news`.*, `ds_news_comm_rdm`.`rdmtime` FROM `ds_news` LEFT JOIN `ds_news_comm_rdm` ON `ds_news`.`id` = `ds_news_comm_rdm`.`modid` AND `ds_news_comm_rdm`.`userid` = "' . core::$user_id . '" ORDER BY `ds_news`.`id` DESC LIMIT 5;');
  $narr = array();
  
  $sql = ''; 
  
  while($data = $res->fetch_assoc())
  {
    $out = array();
    $out['id'] =  $data['id'];
    $out['login'] =  $data['login'];
    $out['user_id'] =  $data['user_id'];
    $out['name'] = text::st($data['name']);
    text::add_cache($data['cache']); 
    $out['text'] = text::out(text::auto_cut($data['text'], 300), 0);
    $out['date'] = ds_time($data['time']);
    $out['time'] = $data['time'];
    $out['comtime'] = $data['comtime'];
    
    $sql .= 'SELECT COUNT(*) FROM `ds_comm` WHERE `module`="news" AND `mid` = "'.$data['id'].'" ;';
    if($out['comtime'] > 0)
      $sql .= 'SELECT COUNT(*) FROM `ds_comm` WHERE `module`="news" AND `mid` = "'.$data['id'].'" AND  `time` > "'.$data['rdmtime'].'" AND `time` > '.(time() - (3 * 24 * 3600)).';';
    if(core::$user_id)
      $sql .= 'SELECT * FROM `ds_news_rdm` WHERE `userid` = "'.core::$user_id.'" AND `artid` = "'.$data['id'].'" ;';

    $narr[] = $out;
  }

if($sql)
  core::$db->multi_query($sql);

$out2 = array();
foreach ($narr AS $key=> $value)
{
  $arr = array();
  $arr = $value;

  //Количество комментариев к статье
  $res = core::$db->store_result()->fetch_row();
  $arr['comm_count'] = $res[0];


  if($arr['comtime'] > 0)
  {
    core::$db->next_result();
    $res = core::$db->store_result()->fetch_row();
    $arr['new_comm_count'] = $res[0];
  }

  //Пытаемся перенаправить юзера к новым комментам
  if($arr['new_comm_count'])
    $arr['page_to_go'] = ceil(($arr['comm_count']-$arr['new_comm_count'])/nav::$kmess);


  if(core::$user_id)
  {
    core::$db->next_result();
    $res = core::$db->store_result()->fetch_array();
    if($res['time'] < $arr['time'] AND $arr['time'] >  (time() - (3 * 24 * 3600)))
      $arr['new_news'] = '1';
  }

  if(core::$db->more_results())
    core::$db->next_result();

  $out2[] = $arr;    
}  

core::$db->multi_free();
$narr = $out2;
  
engine_head(lang('news'));
  if(CAN('news_create', 0))
      temp::assign('can_cr_news', 1);
temp::HTMassign('narr', $narr);
temp::display('news.index');
engine_fin();



