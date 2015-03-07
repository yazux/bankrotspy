<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = GET('idfile');

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_post_files` WHERE  `id` = "'.$id.'" LIMIT 1;');
if($req->num_rows)
{
  $data = $req->fetch_assoc();  
  if($data['name'] != GET('fileload'))  
    denied(); 
  else
  {
     core::$db->query('UPDATE `ds_post_files` SET `count` = "'.($data['count']+1).'" WHERE  `id` = "'.$id.'";');
     new download('./data/att_post/'.$id.'.dat', $data['name']);    
  }    
}
else
  denied();