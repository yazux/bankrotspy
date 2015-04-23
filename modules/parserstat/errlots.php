<?php
defined('DS_ENGINE') or die('web_demon laughs');

new nav; //Постраничная навигация

$total = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_bad_data` ;')->count();
$res = core::$db->query('SELECT * FROM `ds_maindata_bad_data` ORDER BY `id` DESC LIMIT '.nav::$start.', '.nav::$kmess.';');
$rmenu = array();
while($data = $res->fetch_array())
{
  $loc = array();
  $loc['id'] = $data['id'];
  $loc['name'] = text::st($data['lkey']);
  if(!$loc['name'])
    $loc['name'] = text::st('ID: '.$loc['id']);
  $loc['undtext_err'] = text::st($data['errors']);
  $loc['undtext'] = text::st($data['data']);
  $rmenu[] = $loc;
}

engine_head(lang('pstat'));
temp::HTMassign('rmenu',$rmenu);
temp::HTMassign('total',$total);
temp::HTMassign('navigation', nav::display($total, core::$home.'/parserstat/errlots?'));
temp::display('parserstat.errlots');
engine_fin();