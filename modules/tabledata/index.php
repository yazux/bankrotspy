<?php
defined('DS_ENGINE') or die('web_demon laughs');

//Ключ, скорее для виду, сесcия доставит гораздо больше проблем =)
$somevar = 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct';
if($somevar != POST('somevar'))
  exit('Glory Sithis!');

$category = intval(POST('category'));

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
if($category == '-1')
  $conditions['fav_sql'] = '`ds_maindata_favorive`.`user_id` = "'.core::$user_id.'" ';

//Дополнительные условия для LEFT JOIN
$join_conditions = array();
$join_conditions['fav_sql'] = '`ds_maindata_favorive`.`user_id` = "'.core::$user_id.'"';


//Условия для выборки
$selects = array();

$order_conditions = array();

if($svalue)
{
  $selects['search'] = ' MATCH (`ds_maindata`.`name`, `ds_maindata`.`description`) AGAINST ("' . core::$db->res($svalue) . '" IN BOOLEAN MODE) as `rel` ';
  $conditions['search'] = ' MATCH (`ds_maindata`.`name`, `ds_maindata`.`description`) AGAINST ("' . core::$db->res($svalue) . '" IN BOOLEAN MODE) ';
  $order_conditions['search'] = '`rel` DESC ';
}

//Фильтрация потипам
if($types)
{
  $conditions['types'] = ' `type` IN ('.implode(', ', $types).') ';
}

if($begin_date)
  $conditions['starttime'] = ' `ds_maindata`.`start_time` > "'.$begin_date.'" ';

if($end_date)
  $conditions['endtime'] = ' `ds_maindata`.`start_time` < "'.$end_date.'" ';

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
  `ds_maindata_favorive`.`item`
  ' . $select_cond . '
  FROM
  `ds_maindata`
  LEFT JOIN
  `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item` '.$join_cond.'

  '.$where_cond.'

  ' . $order_cond . '

  LIMIT '.$start.', '.$kmess.' ;');


if($svalue)
{
  $item_arr = explode(' ', $svalue);
}

$tabledata = new tabledata();
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
  }
  else
    $types = unserialize(rem::get('maintable_types'));

  if($only_keys)
    return $out;
  else
    return $types;
}

echo json_encode($outdata);