<?php
defined('DS_ENGINE') or die('web_demon laughs');

//Ключ, скорее для виду, сесcия доставит гораздо больше проблем =)
$somevar = 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct';
if($somevar != POST('somevar'))
  exit('Glory Sithis!');

$category = intval(POST('category'));

$sortcolumn = POST('sortcolumn');
$sorttype = intval(abs(POST('sorttype')));
$tabledata = new tabledata($sortcolumn, $sorttype);

$price_search = 'price';
$type_price = abs(intval(POST('type_price')));
{
  if($type_price == 2)
    $price_search = 'now_price';
  elseif($type_price == 3)
    $price_search = 'market_price';
}

$price_start = abs(intval(POST('price_start')));
$price_end = abs(intval(POST('price_end')));

if($price_start AND $price_end)
{
  if($price_start > $price_end)
  {
    $price_start = '';
    $price_end  = '';
  }
}

$altint = POST('altint');
if($altint)
{
  $alt_arr = explode('-', $altint);
  $first_alt = isset($alt_arr[0]) ? abs(intval($alt_arr[0])) : '' ;
  $second_alt = isset($alt_arr[1]) ? abs(intval($alt_arr[1])) : '' ;
}

$begin_date = abs(intval(POST('begin_date')));
$end_date = abs(intval(POST('end_date')));
if($end_date AND $begin_date)
{
  if($end_date < $begin_date)
  {
    $end_date = '';
    $begin_date = '';
  }
}
if($end_date)
  $end_date = $end_date + ((24*3600)-1); //Добавляем без секунду сутки, чтоб было включительно

$types = POST('types');
$types = check_types($types);

$places = POST('places');
$places = check_places($places);

$platforms = POST('platforms');
$platforms = check_platforms($platforms);

$svalue = POST('svalue');
if(mb_strlen($svalue) < 2)
  $svalue = '';

//Постраничная навигация
$now_page = abs(intval(POST('page')));
if(!$now_page)
  $now_page = 1;
$kmess = abs(intval(POST('kmess')));
if($kmess > 40)
  $kmess = 40;
$start =  $now_page ? $now_page * $kmess - $kmess : 0;

//Условия для WHERE
$conditions = array();

//Дополнительные условия для LEFT JOIN
$join_conditions = array();
$join_conditions['fav_sql'] = '`ds_maindata_favorive`.`user_id` = "'.core::$user_id.'"';

//Условия для выборки
$selects = array();

//Условия для сортировки
$order_conditions = array();

//Ностройки сортировки
$sort = $tabledata->get_sort_order();
if($sort)
  $order_conditions['sort'] = $sort;

if($category == '-1')
  $conditions['fav_sql'] = '`ds_maindata_favorive`.`user_id` = "'.core::$user_id.'" ';
elseif($category >= 0)
  $conditions['category'] = ' `ds_maindata`.`cat_id` = "'.$category.'" ';

if($svalue)
{
  $selects['search'] = ' MATCH (`ds_maindata`.`name`, `ds_maindata`.`description`) AGAINST ("' . core::$db->res($svalue) . '" IN BOOLEAN MODE) as `rel` ';
  $conditions['search'] = ' MATCH (`ds_maindata`.`name`, `ds_maindata`.`description`) AGAINST ("' . core::$db->res($svalue) . '" IN BOOLEAN MODE) ';
  $order_conditions['search'] = '`rel` DESC ';
}

//Фильтрация по типам
if($types)
  $conditions['types'] = ' `type` IN ('.implode(', ', $types).') ';

//Фильтрация по регионам
if(count(get_places(true)) != count($places) AND $places)
  $conditions['places'] = ' `ds_maindata`.`place` IN ('.implode(', ', $places).') ';

//Фильтрация по платформам
if(count(get_platforms(true)) != count($platforms) AND $platforms)
  $conditions['platforms'] = ' `ds_maindata`.`platform_id` IN ('.implode(', ', $platforms).') ';

if(!$first_alt AND !$second_alt)
{
  if($begin_date)
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . $begin_date . '" ';

  if($end_date)
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . $end_date . '" ';
}
if(!$begin_date AND !$end_date)
{
  $nowtime = strtotime(date('Y').'-'.date('n').'-'.date('j'));

  if($first_alt AND $second_alt)
  {
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . ($nowtime + ($first_alt*24*3600)) . '" ';
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . (($nowtime + ($second_alt*24*3600))+((3600*24)-1)) . '" ';
  }
  elseif($first_alt)
  {
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . ($nowtime + ($first_alt*24*3600)) . '" ';
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . (($nowtime + ($first_alt*24*3600))+((3600*24)-1)) . '" ';
  }
}

if($price_start)
  $conditions['price_start'] = ' `ds_maindata`.`'.$price_search.'` > "' . $price_start . '" ';

if($price_end)
  $conditions['price_end'] = ' `ds_maindata`.`'.$price_search.'` < "' . $price_end . '" ';

if($price_start OR $price_end)
{
  if($type_price == 3)
    $conditions['price_end_third'] = ' `ds_maindata`.`' . $price_search . '` > "0" ';
}

//Компилим условия
$where_cond = '';
if($conditions)
  $where_cond = ' WHERE '.implode(' AND ', $conditions);

$join_cond = '';
if($join_conditions)
  $join_cond = ' AND '.implode(' AND ', $join_conditions);

$select_cond = '';
if($selects)
  $select_cond = ' , '.implode(' , ', $selects);

$order_cond = '`ds_maindata`.`id` ASC';
if($order_conditions)
  $order_cond = ' ORDER BY '.implode(' , ', $order_conditions).' , `ds_maindata`.`id` ASC ';
else
  $order_cond = ' ORDER BY `ds_maindata`.`id` ASC ';


//Счетчик
$count = core::$db->query('SELECT
  COUNT(*)
  FROM
  `ds_maindata`
  LEFT JOIN
  `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item` '.$join_cond.'

  '.$where_cond.'

  ORDER BY `ds_maindata`.`id`
  ;')->count();

//Основной запрос
$res = core::$db->query('SELECT
  `ds_maindata`.*,
  `ds_maindata_favorive`.`item`,
  `ds_maindata_regions`.`name` AS `regionname`
  ' . $select_cond . '
  FROM
  `ds_maindata`
  LEFT JOIN
  `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item` '.$join_cond.'
  LEFT JOIN
  `ds_maindata_regions` ON `ds_maindata`.`place` = `ds_maindata_regions`.`number`
  LEFT JOIN
  `ds_maindata_platforms` ON `ds_maindata`.`platform_id` = `ds_maindata_platforms`.`id`

  '.$where_cond.'

  ' . $order_cond . '

  LIMIT '.$start.', '.$kmess.' ;');


if($svalue)
{
  $item_arr = explode(' ', $svalue);
}

if($res->num_rows)
{
  while($data = $res->fetch_array())
  {
    $loc = array();

    $loc['number'] = $tabledata->number($data['code'], $data['id']);
    $loc['name'] = $tabledata->name($data['name'], 40, $data['id'],  $item_arr, $data['description']);
    $loc['type'] = $tabledata->type($data['type']);
    $loc['place'] = $tabledata->place($data['place']);
    $loc['begindate'] = $tabledata->begindate($data['start_time']);
    $loc['closedate'] = $tabledata->closedate($data['end_time']);
    $loc['beforedate'] = $tabledata->beforedate($data['start_time'], $data['end_time']);
    $loc['beginprice'] = $tabledata->beginprice($data['price']);
    $loc['nowprice'] = $tabledata->nowprice($data['now_price']);
    $loc['marketprice'] = $tabledata->marketprice($data['market_price']);
    $loc['profitrub'] = $tabledata->profitrub($data['profit_rub']);
    $loc['profitproc'] = $tabledata->profitproc($data['profit_proc']);
    $loc['platform'] = $tabledata->platform($data['platform_id'], $data['auct_link']);
    $loc['favorite'] = $tabledata->favorite($data['id'], $data['item']);
    $out[] = $loc;
  }

  $outdata = array(
    'columns' => $tabledata->get_names(),
    'maindata' => $out,
    'count' => $count
  );

}
else
{
  $outdata = array(
    'columns' => array('name' => array(
      'name' => (($category == '-1' AND !core::$user_id) ? 'Добавление в избранное доступно только зарегистрированным пользователям!' : 'Ничего не найдено!'),
      'style' => 'background:white;border:0px;padding: 40px 0;font-size: 14px;'
    )),
    'maindata' => '',
    'count' => $count
  );
}

function check_platforms($platforms)
{
  $in_platforms = get_platforms();
  $arr = explode('|', $platforms);
  $out = array();
  foreach($arr AS $val)
  {
    if(isset($in_platforms[$val]))
      $out[] = $val;
  }
  if(!$out)
    return get_platforms(true);
  else
    return $out;
}

function get_platforms($only_keys = false)
{
  $platforms = array();
  $out = array();
  if(!rem::exists('maintable_platforms'))
  {
    $res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['id'];
      $platforms[$data['id']] = $data['platform_url'];
    }
    rem::remember('maintable_platforms', serialize($platforms));
    rem::remember('maintable_platforms_out', serialize($out));
  }
  else
  {
    $platforms = unserialize(rem::get('maintable_platforms'));
    $out = unserialize(rem::get('maintable_platforms_out'));
  }

  if($only_keys)
    return $out;
  else
    return $platforms;
}

function check_places($places)
{
  $in_places = get_places();
  $arr = explode('|', $places);
  $out = array();
  foreach($arr AS $val)
  {
    if(isset($in_places[$val]))
      $out[] = $val;
  }
  if(!$out)
    return get_places(true);
  else
    return $out;
}

function get_places($only_keys = false)
{
  $places = array();
  $out = array();
  if(!rem::exists('maintable_places'))
  {
    $res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['number'];
      $places[$data['number']] = $data['name'];
    }
    rem::remember('maintable_places', serialize($places));
    rem::remember('maintable_places_out', serialize($out));
  }
  else
  {
    $places = unserialize(rem::get('maintable_places'));
    $out = unserialize(rem::get('maintable_places_out'));
  }

  if($only_keys)
    return $out;
  else
    return $places;
}

function check_types($types)
{
  $in_types = get_types();
  $arr = explode('|', $types);
  $out = array();
  foreach($arr AS $val)
  {
    if(isset($in_types[$val]))
      $out[] = $val;
  }
  if(!$out)
    return get_types(true);
  else
    return $out;
}

function get_types($only_keys = false)
{
  $types = array();
  $out = array();
  if(!rem::exists('maintable_types'))
  {
    $res = core::$db->query('SELECT * FROM  `ds_maindata_type` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['id'];
      $types[$data['id']] = text::st($data['type_name']);
    }
    rem::remember('maintable_types', serialize($types));
    rem::remember('maintable_types_out', serialize($out));
  }
  else
  {
    $types = unserialize(rem::get('maintable_types'));
    $out = unserialize(rem::get('maintable_types_out'));
  }

  if($only_keys)
    return $out;
  else
    return $types;
}

echo json_encode($outdata);