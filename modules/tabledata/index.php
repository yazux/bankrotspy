<?php
defined('DS_ENGINE') or die('web_demon laughs');


$res = core::$db->query('SELECT * FROM `ds_maindata` LIMIT 20;');
$tabledata = new tabledata();

while($data = $res->fetch_array())
{
  $loc = array();

  $loc['name'] = $tabledata->name($data['name'], 40, $data['id']);
  $loc['type'] = $tabledata->type($data['type']);
  $loc['begindate'] = $tabledata->begindate($data['start_time']);
  $loc['closedate'] = $tabledata->closedate($data['end_time']);
  $loc['beforedate'] = $tabledata->beforedate($data['start_time'], $data['end_time']);
  $loc['beginprice'] = $tabledata->beginprice($data['price']);
  $loc['nowprice'] = $tabledata->nowprice($data['now_price']);
  $out[] = $loc;
}

$outdata = array(
  'columns' => $tabledata->get_names(),
  'maindata' => $out
);

echo json_encode($outdata);