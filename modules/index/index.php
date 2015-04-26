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

  //$rights_arr[100]['premium_content'] = 1;


  //echo '<b>admin:</b><br/>';
  //echo(serialize($rights_arr[100]));
  
  //echo '<br/><br/>';
  
  //echo '<b>user:</b><br/>';
  //echo(serialize($rights_arr[0]));


////////////////////////////////////////////////////////////

$platforms = array();
$platforms_def = array();
$res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ORDER BY `platform_url` ASC;;');
while($data = $res->fetch_array())
{
  $platforms_def[$data['id']] = 1;
  $platforms[] = array($data['id'], $data['platform_url']);
}

//Раскидываем по колонкам
$half_platform = ceil(count($platforms)/2);
$now_platforms = array();
$i = 0;
while($i <= $half_platform)
{
  if(isset($platforms[$i]))
    $now_platforms[$platforms[$i][0]] = $platforms[$i][1];
  if(isset($platforms[$i+$half_platform]))
    $now_platforms[$platforms[$i+$half_platform][0]] = $platforms[$i+$half_platform][1];
  $i++;
}
$platforms = $now_platforms;

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
$res = core::$db->query('SELECT * FROM `ds_maindata_category` ORDER BY `usort` ASC;');
while($data = $res->fetch_array())
{
  $categories[$data['id']] = text::st($data['name']);
}


//Настройки по умолчанию
$set_table_array = defaultset::get(array($types_def, $places_def, $platforms_def));

//Сохраненный поиск пользователя
if(core::$user_id AND isset(core::$user_set['tabledata']) AND core::$user_set['tabledata'])
{
  $save_set = array();
  $user_tset = json_decode(core::$user_set['tabledata'], 1);
  //Проходимся еще раз, вдруг в таблице добавились новые настройки
  foreach($set_table_array AS $key => $value)
  {
    if(isset($user_tset[$key]))
      $save_set[$key] = $user_tset[$key];
    else
      $save_set[$key] = $value;
  }
  $places_used = $user_tset['places'];
  $platforms_used = $user_tset['platforms'];
}

//Новости по лотам
$outnews = array();
$res = core::$db->query('SELECT * FROM `ds_platform_news` LIMIT 10;');
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['time'] = ds_time($data['time'], '%H:%M');
  $loc['data'] = ds_time($data['time'], '%d %B2 %Y');
  text::add_cache($data['cache']);
  $loc['text'] = text::out($data['text'], 0);
  $outnews[] = $loc;
}

engine_head();
temp::HTMassign('outnews', $outnews);
temp::assign('table_default_set', json_encode($set_table_array));
temp::assign('table_set', (isset($save_set) ? json_encode($save_set) : json_encode($set_table_array)));
temp::HTMassign('types_set', $types);
temp::HTMassign('types_def', $types_def);
temp::HTMassign('categories', $categories);
temp::HTMassign('places', $places);
temp::HTMassign('places_used', isset($places_used) ? $places_used : $places_def);

temp::HTMassign('bold_places', $bold_places);
temp::HTMassign('places_def', $places_def);

temp::HTMassign('platforms', $platforms);
temp::HTMassign('platforms_def', $platforms_def);
temp::HTMassign('platforms_used', isset($platforms_used) ? $platforms_used : $platforms_def);

temp::assign('type_price', $set_table_array['type_price']);
temp::display('index.index');
engine_fin();