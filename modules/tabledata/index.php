<?php
defined('DS_ENGINE') or die('web_demon laughs');


$res = core::$db->query('SELECT
  `ds_maindata`.*,
  `ds_maindata_favorive`.`item`
  FROM
  `ds_maindata`
  LEFT JOIN
  `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item`
  WHERE
  `ds_maindata_favorive`.`user_id` = "'.core::$user_id.'" OR `ds_maindata_favorive`.`user_id` IS NULL

  ORDER BY `ds_maindata`.`id`

  LIMIT 20 ;');
$tabledata = new tabledata();

while($data = $res->fetch_array())
{
  $loc = array();

  $loc['number'] = $tabledata->number($data['code'], $data['id']);
  $loc['name'] = $tabledata->name($data['name'], 40, $data['id']);
  $loc['type'] = $tabledata->type($data['type']);
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
  'maindata' => $out
);

echo json_encode($outdata);