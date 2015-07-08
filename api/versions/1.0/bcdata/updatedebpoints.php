<?php
defined('DS_ENGINE') or die('web_demon laughs');

$getdata = POST('itemdata');
$getdata = iconv("windows-1251", "UTF-8", $getdata);

if(!$getdata)
  exit('No item data');

$dataarr = array('');
$getarr = explode(';', $getdata);
$dataarr = array_merge($dataarr, $getarr);

//Избавляемся от кавычек
$new_drarr = array();
foreach($dataarr AS $kkey=>$vval)
{
  $vval = trim($vval);
  $first_symbol = mb_substr($vval, 0, 1);
  $len = mb_strlen($vval);
  $end_symbol = mb_substr($vval, ($len-1), 1);
  if($first_symbol == '"' AND $end_symbol == '"')
  {
    $vval = mb_substr($vval, 1, ($len-2));
  }
  $new_drarr[$kkey] = $vval;
}
$dataarr = $new_drarr;

//Для лучшего понимания
$sourse = array();
$sourse['lotid']         = intval(abs(trim($dataarr[1])));
$sourse['debpoints']         = $dataarr[2];
if(isset($dataarr[3]) AND $dataarr[3])
  $sourse['debnotice'] =  htmlentities(trim($dataarr[3]), ENT_QUOTES, 'UTF-8');

if(!$sourse['lotid'])
  exit('No item id');

//Достаем данные
$req = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "'.core::$db->res($sourse['lotid']).'" ;');
if(!$req->num_rows)
  exit('Wrong item id');

$data = $req->fetch_assoc();

//Лоадер файлов-функций
$load = new bcdata();
$error = array();

//Для вывода
$outdata = array();

//Названия те же что в таблице
$outdata['debpoints'] = abs(intval($sourse['debpoints']));
if(isset($sourse['debnotice']) AND $sourse['debnotice'])
  $outdata['debnotice'] = $sourse['debnotice'];
$outdata['debicheck'] = time();

//print_r($outdata);

if(!$outdata['debpoints'])
  $error[] = 'No debpoints for id: '.$sourse['lotid'];

if(!$error)
{
  $out = array();
  foreach($outdata AS $key=>$value)
    $out[] = ' `'.$key.'` = "'.core::$db->res($value).'" ';

  if($out)
  {
    $part_query = implode(', ', $out);
    core::$db->query('UPDATE `ds_maindata` SET '.$part_query.' WHERE `id` = "'.core::$db->res($sourse['lotid']).'";');
    echo 'ok';
  }
}
else
{
  echo implode(', ', $error);

  $old_file_data = file_get_contents('./logs/itemdata.log');
  file_put_contents('./logs/itemdata.log', $old_file_data."\n".'updatedebpoints: '.$getdata.' - '. implode(', ', $error));
}
