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
$bold_regions_set = array(77, 78, 50, 47); //Регионы в топе (выделены жирным)
$bold_places = [];
//Достаем регионы
$places_def = [];
$places = [];
$res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ORDER BY `name` ASC;');

while($data = $res->fetch_assoc()) {
    
    if($data['parent_id'] !== 0 && (int)$data['number'] != -1) {
        $places_def[$data['number']] = 1;
    }
    
    //if ($data['number'] == 0) continue;
    
    $regions[] = $data;
}

$regions_intop = [77, 78, 50, 47, 0];
$regions_top_tmp = [];
$regions_top = [];
$regions_result = [];

foreach($regions as $region) {
    
    if (in_array($region['number'], $regions_intop)) {
        $regions_top_tmp[$region['number']] = $region['name'];
    } elseif ($region['parent_id'] == 0) {
        $regions_result[$region['id']]['name'] = $region['name'];
        foreach($regions as $subregion) {
            if($subregion['parent_id'] == $region['id'] && !in_array($subregion['number'], $regions_intop)) {
                $regions_result[$region['id']]['sub'][$subregion['number']] = $subregion['name'];
            }
        }
    }
}
foreach ($regions_intop as $key) {
    $regions_top[$key] = $regions_top_tmp[$key];
}

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
$new_lots = $set_table_array['new_lots'];

//Сохраненный поиск пользователя
if(core::$user_id AND isset(core::$user_set['tabledata']) AND core::$user_set['tabledata'])
{
  $res = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res(core::$user_set['tabledata']).'" AND `userid` = "'.core::$user_id.'";');
  if($res->num_rows)
  {
    $pdata = $res->fetch_array();
    $save_set = array();
    $user_tset = json_decode($pdata['profile'], 1);
    
    //Проходимся еще раз, вдруг в таблице добавились новые настройки
    foreach($set_table_array AS $key => $value)
    {
      if(isset($user_tset[$key]))
        $save_set[$key] = $user_tset[$key];
      else
        $save_set[$key] = $value;
    }
    $new_lots = $save_set['new_lots'];
    
    $places_used = $user_tset['places'];
    $platforms_used = $user_tset['platforms'];
    //var_dump($places_used);
    //Текущий профиль
    $now_profile_name = $pdata['pname'];
    $now_profile_id = $pdata['id'];
    $default_profile_id = core::$user_set['defprofile'];

    //Достаем все профили
    $outprofiles = array();
    $res = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `userid` = "'.core::$user_id.'";');
    while($prd = $res->fetch_array())
    {
      $loc = array();
      $loc['id'] = $prd['id'];
      $loc['name'] = $prd['pname'];
      $outprofiles[] = $loc;
    }
  }
}

//Новости по лотам
$outnews = array();
$res = core::$db->query('SELECT * FROM `ds_platform_news` ORDER BY `time` DESC LIMIT 10;');
while($data = $res->fetch_array()) {
    $loc = array();
    $loc['id'] = $data['id'];
    $loc['time'] = ds_time($data['time'], '%H:%M');
    $loc['data'] = ds_time($data['time'], '%d %B2 %Y');
    text::add_cache($data['cache']);
    $loc['text'] = text::out($data['text'], 0);
    $outnews[] = $loc;
}

// костыль вывода статьи на лавной
$articleData = core::$db->query('SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id` WHERE `ds_article`.`id` = "8" ;');
$articleData = $articleData->fetch_assoc();

$article['name'] = htmlentities($articleData['name'], ENT_QUOTES, 'UTF-8');
$article['text'] = text::out($articleData['text'], 0, $articleData['id']);
$article['text'] = fload::replace_files($article['text'], $articleData['id'], core::$module);
$article['time'] = ds_time($articleData['time']);

engine_head();

// закрепленные области
temp::HTMassign('regions_top', $regions_top);
temp::HTMassign('regions_result', $regions_result);
temp::HTMassign('places_used', isset($places_used) ? $places_used : $places_def);
temp::HTMassign('places_def', $places_def);
temp::HTMassign('article', $article);
temp::HTMassign('outnews', $outnews);
temp::assign('table_default_set', json_encode($set_table_array));
temp::assign('table_set', (isset($save_set) ? json_encode($save_set) : json_encode($set_table_array)));
temp::HTMassign('types_set', $types);
temp::HTMassign('types_def', $types_def);
temp::HTMassign('categories', $categories);

// Вывод рекламного блока
if ( isset(core::$set['main_adv_text']) && (core::$set['main_adv_text'] != '') ) {
    temp::HTMassign('main_adv_text', text::out(core::$set['main_adv_text'], 0));
//    temp::HTMassign('main_adv_text', core::$set['main_adv_text']);
}

if(isset($now_profile_id)) {
    temp::assign('now_profile_id', $now_profile_id);
    temp::assign('now_profile_name', $now_profile_name);
    temp::assign('default_profile_id', $default_profile_id);
    temp::HTMassign('outprofiles', $outprofiles);
}

temp::HTMassign('new_lots', $new_lots);
temp::HTMassign('platforms', $platforms);
temp::HTMassign('platforms_def', $platforms_def);
temp::HTMassign('platforms_used', isset($platforms_used) ? $platforms_used : $platforms_def);
temp::assign('type_price', $set_table_array['type_price']);
temp::display('index.index');
engine_fin();