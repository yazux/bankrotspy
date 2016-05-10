<?php
defined('DS_ENGINE') or die('web_demon laughs');

$getdata = POST('itemdata');
$unicode = POST('unicode');
$dontadderrors = POST('dontadderrors');
if(!$unicode)
    $getdata = iconv("windows-1251", "UTF-8", $getdata);

if(!$getdata)
    exit('No item data');

//Разбираем полученные данные, получаем sql-запрос
$sql = loaditem::generete_sql($getdata);

if( $sql ) {
    $id_lot = loaditem::get_id();
    $req = core::$db->query('SELECT * FROM `ds_maindata` WHERE `item_key` = "'.core::$db->res($id_lot).'" ;');
    if(!$req->num_rows) {
      core::$db->query($sql);
      $last_ins = core::$db->insert_id;
      core::$db->query('INSERT INTO `ds_maindata_source` SET `lotid` = "' . core::$db->res($last_ins) . '", `lkey` = "' . core::$db->res($id_lot) . '", `data` = "' . core::$db->res($getdata) . '" ;');
      echo 'ok';
    } else
        exit('Item already exists');
} else {
    $id_lot = loaditem::get_id();
    if(!$dontadderrors)
        core::$db->query('INSERT INTO `ds_maindata_bad_data` SET `lkey` = "'.core::$db->res($id_lot).'", `data` = "'.core::$db->res($getdata).'", `errors` = "'.core::$db->res(loaditem::get_error()).'" ;');
    echo loaditem::get_error();
}