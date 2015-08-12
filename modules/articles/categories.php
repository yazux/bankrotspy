<?php
defined('DS_ENGINE') or die('web_demon laughs');

$total = core::$db->query('SELECT COUNT(*) FROM `ds_art_keywords`;')->count();

new nav; //Постраничная навигация

$res = core::$db->query('SELECT * FROM `ds_art_keywords` ORDER BY `keyname` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');

if($total > 0 AND !$res->num_rows)
   denied();

$sql = '';

$out = array();
$out2 = array();
while($data = $res->fetch_assoc())
{
  $arr = array();  
  $arr['id'] = $data['id'];
  $arr['name'] = $data['keyname'];
  $sql .= 'SELECT COUNT(*) FROM `ds_art_keytable` WHERE `keyid` = "'.$data['id'].'";';
  $out[] = $arr;  
}

if($sql)
  core::$db->multi_query($sql);

foreach ($out AS $key=> $value)
{
  $arr = array();
  $arr = $value;
  
  core::$db->next_result();
  $res = core::$db->store_result()->fetch_row();
  $arr['count'] = $res[0];
    
  $out2[] = $arr;    
}

core::$db->multi_free();
$out = $out2;

engine_head(lang('cat_stat'));
temp::HTMassign('out', $out);
temp::assign('total', $total);
if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
    temp::assign('can_cr_stat', 1);
temp::HTMassign('navigation', nav::display($total, core::$home.'/articles/categories?'));
temp::display('articles.categories');
engine_fin();