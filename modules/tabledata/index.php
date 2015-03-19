<?php
defined('DS_ENGINE') or die('web_demon laughs');

//Ключ, скорее для виду, сесcия доставит гораздо больше проблем =)
$somevar = 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct';
if($somevar != POST('somevar'))
  exit('Glory Sithis!');

$category = intval(POST('category'));

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

//Компилим условия
$where_cond = '';
if($conditions)
  $where_cond = ' WHERE '.implode(' ', $conditions);

$join_cond = '';
if($join_conditions)
  $join_cond = ' AND '.implode(' ', $join_conditions);

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
  FROM
  `ds_maindata`
  LEFT JOIN
  `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item` '.$join_cond.'

  '.$where_cond.'

  ORDER BY `ds_maindata`.`id`

  LIMIT '.$start.', '.$kmess.' ;');



$tabledata = new tabledata();
if($res->num_rows)
{
  while($data = $res->fetch_array())
  {
    $loc = array();

    $loc['number'] = $tabledata->number($data['code'], $data['id']);
    $loc['name'] = $tabledata->name($data['name'], 40, $data['id']);
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

echo json_encode($outdata);