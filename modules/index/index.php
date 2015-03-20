<?php
defined('DS_ENGINE') or die('web_demon laughs');


$res = core::$db->query('SELECT * FROM  `ds_rights` ');
  while($data = $res->fetch_array())
  {
    $rights_arr[$data['id']] =  unserialize($data['rights']); 
  }

  $rights_arr[100]['tech_support'] = 1;


  //echo '<b>admin:</b><br/>';
  //echo(serialize($rights_arr[100]));
  
  //echo '<br/><br/>';
  
  //echo '<b>user:</b><br/>';
  //echo(serialize($rights_arr[0]));

  $set_table_array = array(
    'category' => 1,
    'page' => 1,
    'kmess' => 20,
    'svalue' => ''
  );



engine_head();
temp::assign('table_set', json_encode($set_table_array));
temp::display('index.index');
engine_fin();