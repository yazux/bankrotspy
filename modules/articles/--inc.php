<?php
defined('DS_ENGINE') or die('web_demon laughs');

$max_name_lenght = 400;
$max_stst_lenght = 40000;
$max_key_lenght = 50;
$min_key_lenght = 2;
$max_col_keys = 10;

function get_keys()
{
  $at_k = core::$db->query('SELECT * FROM `ds_art_keywords`;');
  $art_keys = array();
  while ($data = $at_k->fetch_assoc())
  {
     $art_keys[$data['id']] = $data['keyname']; 
  }    
  return $art_keys;  
}

$art_keys = get_keys();

function keys2text($ser_array)
{
  global $art_keys;
  $array = unserialize($ser_array);
  $out = array();
  foreach($art_keys AS $key=>$value)
  {
    if(in_array($key, $array))
      $out[] = $value;  
  }
  return implode(', ', $out);  
}

function del_tags($array)
{
  $query = '';
  $arr = array();  
  foreach($array AS $value)
  {
    $query .= 'SELECT COUNT(*) FROM `ds_art_keytable` WHERE `keyid` = "'.$value.'";';
    $arr[] = $value;   
  }
  if($query)
  {
    core::$db->multi_query($query);  
    $query = '';
    
    foreach ($arr AS $value)
    {
      core::$db->next_result();
      $res = core::$db->store_result()->fetch_row();
      if($res[0] == 0)
        $query .= 'DELETE FROM `ds_art_keywords` WHERE `id` = "'.$value.'";'; 
    } 
    core::$db->multi_free();
    if($query)
    {
      core::$db->multi_query($query);
      core::$db->multi_free();
    }
  } 
}
