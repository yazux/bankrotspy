<?php
defined('DS_ENGINE') or die('web_demon laughs');

/////////////////////////////////////////////////////////////
//  Это мусор для прав
//////////////////////////////////////////////////////////////
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


////////////////////////////////////////////////////////////

//Типы предложений в таблице
$types = array();
$types_def = array();
$res = core::$db->query('SELECT * FROM  `ds_maindata_type` ;');
while($data = $res->fetch_array())
{
  $types_def[$data['id']] = 1;
  $types[$data['id']] = text::st($data['type_name']);
}

  $set_table_array = array(
    'category' => -2,
    'page' => 1,
    'kmess' => 20,
    'svalue' => '',
    'types' => $types_def,
    'begin_date' => '',
    'end_date' => ''
  );

engine_head();
temp::assign('table_default_set', json_encode($set_table_array));
temp::assign('table_set', json_encode($set_table_array));
temp::HTMassign('types_set', $types);
temp::HTMassign('types_def', $types_def);
temp::display('index.index');
engine_fin();