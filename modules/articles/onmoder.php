<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!CAN('stats_moderate', 0))
  denied();

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_article` WHERE `ds_article`.`type` = "2";')->count();

$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`type` = "2" ORDER BY `ds_article`.`time` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');
if($total > 0 AND !$res->num_rows)
  denied();

$sql = '';

$out = array();
$out2 = array();
while($data = $res->fetch_assoc())
{
  $arr = array();
  $arr['name'] = htmlentities($data['name'], ENT_QUOTES, 'UTF-8');
  text::add_cache($data['cache']);
  $arr['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
  $arr['text'] =  text::out(text::auto_cut($data['text'], 800), 0, $data['id']);
  $arr['id'] = $data['id'];
  $arr['userid'] = $data['userid'];
  $arr['user'] = $data['autor'];
  $arr['time'] = ds_time($data['time']);
  $arr['rating'] = ($data['rating_plus']-$data['rating_minus']);

  $sql .= 'SELECT * FROM `ds_post_files` WHERE `post` = "'.$data['id'].'" AND `module` = "'.core::$module.'";';
  $sql .= 'SELECT COUNT(*) FROM `ds_comm` WHERE `module`="articles" AND `mid` = "'.$data['id'].'" ;';
  $sql .= 'SELECT COUNT(*) FROM `ds_art_rdm` WHERE `artid` = "'.$data['id'].'" AND `userid` = "'.core::$user_id.'";';

  $arr['draft_keys'] = unserialize($data['keywords_hide']);
  $out[] = $arr;
}

if($sql)
  core::$db->multi_query($sql);

foreach ($out AS $key=> $value)
{
  $arr = array();
  $arr = $value;

  //Разбираемся с прикрепленными файлами
  core::$db->next_result();
  $fil1 = core::$db->store_result();
  $out_files = array();
  while ($fil = $fil1->fetch_assoc())
  {
    $fname = $fil['name'];
    $out_files[$fname] = array();
    $out_files[$fname]['filename'] = $fname;
    $out_files[$fname]['name'] = $fname;
    $out_files[$fname]['id'] = $fil['id'];
  }

  $arr['text'] = fload::replace_files_arr($arr['text'], $out_files);

  //Количество комментариев к статье
  core::$db->next_result();
  $res = core::$db->store_result()->fetch_row();
  $arr['comm_count'] = $res[0];

  //Прочитана статья или нет
  core::$db->next_result();
  $res = core::$db->store_result()->fetch_row();

  if($res[0] > 0)
    $arr['is_new'] = 0;
  elseif($arr['time'] > (time() - (3 * 24 * 3600)))
    $arr['is_new'] = 1;
  else
    $arr['is_new'] = 0;

  $out2[] = $arr;
}

core::$db->multi_free();
$out = $out2;

engine_head(lang('onmoder'));
temp::HTMassign('out', $out);
if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
  temp::assign('can_cr_stat', 1);

temp::HTMassign('navigation', nav::display($total, core::$home.'/articles/allarticles?'));
temp::display('articles.onmoder');
engine_fin();