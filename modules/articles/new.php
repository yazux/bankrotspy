<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_article` LEFT JOIN `ds_art_rdm` ON `ds_article`.`id` = `ds_art_rdm`.`artid` AND `ds_art_rdm`.`userid` = "' . core::$user_id . '" WHERE `ds_art_rdm`.`userid` IS NULL AND `ds_article`.`type` = "0" AND `ds_article`.`time` > "'.(time() - (3 * 24 * 3600)).'";')->count();

$res = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` LEFT JOIN `ds_art_rdm` ON `ds_article`.`id` = `ds_art_rdm`.`artid` AND  `ds_art_rdm`.`userid` = "' . core::$user_id . '" WHERE `ds_art_rdm`.`userid` IS NULL AND `ds_article`.`time` > "'.(time() - (3 * 24 * 3600)).'" AND `ds_article`.`type` = "0" ORDER BY `ds_article`.`time` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');
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
  
  $keys = array();
  $keys_data = array();
  $keys_data = unserialize($data['keywords']);
  if($keys_data)
  {
    foreach($keys_data AS $kid)
    {
      if(isset($art_keys[$kid]))
        $keys[$kid] = $art_keys[$kid];  
    }
  }
  $arr['keys'] = $keys;
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
    $out_files[$fil['name']] = array();
    $out_files[$fil['name']]['filename'] = $fil['name'];
    $out_files[$fil['name']]['name'] = $fil['name'];
    $out_files[$fil['name']]['id'] = $fil['id']; 
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

engine_head(lang('new_stat'));
temp::HTMassign('out', $out);
if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
    temp::assign('can_cr_stat', 1);

temp::HTMassign('navigation', nav::display($total, core::$home.'/articles/new?'));
temp::display('articles.new');
engine_fin();