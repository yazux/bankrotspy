<?php
defined('DS_ENGINE') or die('web_demon laughs');


$res = core::$db->query('SELECT * FROM `ds_maindata` LIMIT 20;');
$tabledata = new tabledata();

while($data = $res->fetch_array())
{
  $loc = array();

  $loc['name'] = $tabledata->name($data['name'], 40, $data['id']);

  $out[] = $loc;
}

$outdata = array(
  'columns' => $tabledata->get_names(),
  'maindata' => $out
);

echo json_encode($outdata);