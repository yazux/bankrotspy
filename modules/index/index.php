<?php
defined('DS_ENGINE') or die('web_demon laughs');
engine_head();

$res = core::$db->query('SELECT * FROM  `ds_rights` ');
  while($data = $res->fetch_array())
  {
    $rights_arr[$data['id']] =  unserialize($data['rights']); 
  }

  $rights_arr[100]['frm_view_trash'] = 1;
  
  echo '<b>admin:</b><br/>';
  echo(serialize($rights_arr[100]));
  
  echo '<br/><br/>'; 
  
  echo '<b>user:</b><br/>';
  echo(serialize($rights_arr[0]));   
temp::display('index.index');
engine_fin();