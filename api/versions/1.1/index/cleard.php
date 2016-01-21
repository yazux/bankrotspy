<?php
defined('DS_ENGINE') or die('web_demon laughs');

$i = 0;
$res = core::$db->query('SELECT * FROM `ds_maindata_debtors` ORDER BY `id`;');
while($data = $res->fetch_assoc())
{
  $cont_lots_deb = core::$db->query('SELECT COUNT(*) FROM `ds_maindata` WHERE `debtor` = "'.core::$db->res($data['id']).'" ;')->count();
  if(!$cont_lots_deb)
  {
    $i++;
    echo implode(' - ', $data).'<br/>';
    core::$db->query('DELETE FROM `ds_maindata_debtors` WHERE `id` = "' . core::$db->res($data['id']) . '" ;');
  }
}

echo '<hr/>';

$i = 0;
$res = core::$db->query('SELECT * FROM `ds_maindata_organizers` ORDER BY `id`;');
while($data = $res->fetch_assoc())
{
  $cont_lots_deb = core::$db->query('SELECT COUNT(*) FROM `ds_maindata` WHERE `organizer` = "'.core::$db->res($data['id']).'" ;')->count();
  if(!$cont_lots_deb)
  {
    $i++;
    echo implode(' - ', $data).'<br/>';
    core::$db->query('DELETE FROM `ds_maindata_organizers` WHERE `id` = "' . core::$db->res($data['id']) . '" ;');
  }
}