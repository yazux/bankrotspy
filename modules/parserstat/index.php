<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_parser_stat` ;')->count();
$res = core::$db->query('SELECT * FROM `ds_parser_stat` ORDER BY `time1` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['name'] = ds_time($data['time1']);
  $loc['undtext'] = text::st($data['msg']);
  $rmenu[] = $loc;
}

engine_head(lang('pstat'));
temp::HTMassign('rmenu',$rmenu);
temp::HTMassign('total',$total);
temp::HTMassign('navigation', nav::display($total, core::$home.'/parserstat?'));
temp::display('parserstat.index');
engine_fin();