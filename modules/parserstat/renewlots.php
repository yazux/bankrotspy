<?php
defined('DS_ENGINE') or die('web_demon laughs');

$total = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_bad_data` ;')->count();
$res = core::$db->query('SELECT * FROM `ds_maindata_bad_data` ORDER BY `id`;');
$rmenu = array();

$reinsered = 0;

$lots = array();
while($data = $res->fetch_array())
{
  $lots[] = array('id' => $data['id'], 'data' => $data['data']);
}

engine_head(lang('pstat'));
temp::HTMassign('lots', json_encode($lots));
temp::assign('total',$total);
temp::display('parserstat.renewlots');
engine_fin();