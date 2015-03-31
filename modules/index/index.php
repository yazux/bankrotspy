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

  $rights_arr[100]['news_create'] = 1;
  $rights_arr[100]['news_edit'] = 1;
  $rights_arr[100]['news_delete'] = 1;


  //echo '<b>admin:</b><br/>';
  //echo(serialize($rights_arr[100]));
  
  //echo '<br/><br/>';
  
  //echo '<b>user:</b><br/>';
  //echo(serialize($rights_arr[0]));


////////////////////////////////////////////////////////////

$platforms = array();
$platforms_def = array();
$res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ;');
while($data = $res->fetch_array())
{
  $platforms_def[$data['id']] = 1;
  $platforms[$data['id']] = $data['platform_url'];
}

//Список регионов
$bold_regions_set = array(77, 78, 50, 47);
$bold_places = array();
//Достаем регионы
$places_def = array();
$places = array();
$res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ORDER BY `name` ASC;');
while($data = $res->fetch_array())
{
  $places_def[$data['number']] = 1;
  if(in_array($data['number'], $bold_regions_set))
    $bold_places[$data['number']] = $data['name'];
  else
    $places[] = array($data['number'], $data['name']);
}

//Произвольная сортировка регионов вначале
$new_bold_places = array();
foreach ($bold_regions_set  AS $val)
  $new_bold_places[$val] = $bold_places[$val];
$bold_places = $new_bold_places;

//Раскидываем по колонкам
$half = ceil(count($places)/2);
$now_places = array();
$i = 0;
while($i <= $half)
{
  if(isset($places[$i]))
    $now_places[$places[$i][0]] = $places[$i][1];
  if(isset($places[$i+$half]))
    $now_places[$places[$i+$half][0]] = $places[$i+$half][1];
  $i++;
}

$places = $now_places;

//Типы предложений в таблице
$types = array();
$types_def = array();
$res = core::$db->query('SELECT * FROM `ds_maindata_type` ;');
while($data = $res->fetch_array())
{
  $types_def[$data['id']] = 1;
  $types[$data['id']] = text::st($data['type_name']);
}

$categories = array();
$res = core::$db->query('SELECT * FROM `ds_maindata_category` ORDER BY `id` ASC;');
while($data = $res->fetch_array())
{
  $categories[$data['id']] = text::st($data['name']);
}

  //Настройки по умолчанию
  $set_table_array = array(
    'category' => -2,
    'page' => 1,
    'kmess' => 20,
    'svalue' => '',
    'types' => $types_def,
    'begin_date' => '',
    'end_date' => '',
    'altint' => '',
    'price_start' => '',
    'price_end' => '',
    'type_price' => 1,
    'sortcolumn' => '',
    'sorttype' => '',
    'places' => $places_def,
    'platforms' => $places_def,
  );

engine_head();
temp::assign('table_default_set', json_encode($set_table_array));
temp::assign('table_set', json_encode($set_table_array));
temp::HTMassign('types_set', $types);
temp::HTMassign('types_def', $types_def);
temp::HTMassign('categories', $categories);
temp::HTMassign('places', $places);
temp::HTMassign('bold_places', $bold_places);
temp::HTMassign('places_def', $places_def);

temp::HTMassign('platforms', $platforms);
temp::HTMassign('platforms_def', $platforms_def);

temp::assign('type_price', $set_table_array['type_price']);
temp::display('index.index');
engine_fin();