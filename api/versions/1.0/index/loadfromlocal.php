<?php
defined('DS_ENGINE') or die('web_demon laughs');

//Исправить с локального файла на загрузку
$data = file_get_contents('../data/utender.ru-data-2015-04-18-21-29-10.csv');

$data = iconv("windows-1251", "UTF-8", $data);
$data = explode("\n", str_replace("\r\n", "\n", $data));
unset($data[0]);

foreach($data AS $val)
{
  $val = trim($val);
  if($val)
  {
    $sql = loaditem::generete_sql($val);
    if($sql)
    {
      $id_lot = loaditem::get_id();
      core::$db->query($sql);
      $last_ins = core::$db->insert_id;
      core::$db->query('INSERT INTO `ds_maindata_source` SET `lotid` = "' . core::$db->res($last_ins) . '", `lkey` = "' . core::$db->res($id_lot) . '", `data` = "' . core::$db->res($val) . '" ;');

      echo $id_lot.' - ok<br/>';
    }
    else
    {
      $id_lot = loaditem::get_id();
      core::$db->query('INSERT INTO `ds_maindata_bad_data` SET `lkey` = "'.core::$db->res($id_lot).'", `data` = "'.core::$db->res($val).'", `errors` = "'.core::$db->res(loaditem::get_error()).'" ;');
      echo $id_lot.' - error: '.loaditem::get_error().'<br/>';
    }
  }
}