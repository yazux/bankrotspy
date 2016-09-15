<?php

defined('DS_ENGINE') or die('web_demon laughs');

// Топ за месяц
$timest = time() - 2629743;

$query = "SELECT lot_id, COUNT(lot_id) as cnt"
    ." FROM lot_views"
    ." WHERE view_date > ".$timest
    ." group by lot_id "
    ." order by cnt desc"
    ." limit 10";

$res = core::$db->query($query);

$data_m = [];
$i = 0;

while($row = $res->fetch_assoc()) {
    $data_m[$i]['num'] = $i+1;
    $data_m[$i]['views'] = $row['cnt'];
    $data_m[$i]['id'] = $row['lot_id'];

    $lotQuery = core::$db->query('SELECT
   `ds_maindata`.*,
   `ds_maindata_regions`.`name` AS `regionname`,
   `ds_maindata_type`.`type_name`,
   `ds_maindata_status`.`status_name`,
   `ds_maindata_platforms`.`platform_url`,
   `ds_maindata_category`.`name` AS `catname`,
   `ds_maindata_hint`.`link` AS `link`,
   `ds_maindata_hint`.`price` AS `price_average`,
   `ds_maindata_hint`.`text` AS `hint`,
   `lot_notes`.`text` AS `note`
    FROM
   `ds_maindata`
    LEFT JOIN `lot_notes` ON `lot_notes`.`lot_id` = `ds_maindata`.`id` AND `lot_notes`.`user_id` = "'.core::$user_id.'"
    LEFT JOIN `ds_maindata_regions` ON `ds_maindata`.`place` = `ds_maindata_regions`.`number`
    LEFT JOIN `ds_maindata_type` ON `ds_maindata`.`type` = `ds_maindata_type`.`id`
    LEFT JOIN `ds_maindata_status` ON `ds_maindata`.`status` = `ds_maindata_status`.`id`
    LEFT JOIN `ds_maindata_platforms` ON `ds_maindata`.`platform_id` = `ds_maindata_platforms`.`id`
    LEFT JOIN `ds_maindata_category` ON `ds_maindata`.`cat_id` = `ds_maindata_category`.`id`
    LEFT JOIN `ds_maindata_hint` ON `ds_maindata`.`id` = `ds_maindata_hint`.`id`
    WHERE `ds_maindata`.`id` = "'.$row['lot_id'].'"
    ;');

    $lot_data =  $lotQuery->fetch_assoc();

    if (mb_strlen($lot_data['name']) > 70){
        $data_m[$i]['name'] = mb_substr($lot_data['name'], 0, 70) . "...";
    } else {
        $data_m[$i]['name'] = $lot_data['name'];
    }
    $data_m[$i]['type'] = ($lot_data['type'] == 1)? "ОА" : "ПП";
    $data_m[$i]['place'] = $lot_data['regionname'];
    $data_m[$i]['status'] = $lot_data['status_name'];
    $data_m[$i]['price'] = intval($lot_data['price']);
    $data_m[$i]['now_price'] = $lot_data['now_price'];
    $data_m[$i]['market_price'] = ($access === true)? $lot_data['now_price'] : $access;

    $i++;
}


// Топ за все время
$query = "SELECT lot_id, COUNT(lot_id) as cnt"
    ." FROM lot_views"
    ." group by lot_id "
    ." order by cnt desc"
    ." limit 10";

$res = core::$db->query($query);

$data_a = [];
$i = 0;

while($row = $res->fetch_assoc()) {
    $data_a[$i]['num'] = $i+1;
    $data_a[$i]['views'] = $row['cnt'];
    $data_a[$i]['id'] = $row['lot_id'];

    $lotQuery = core::$db->query('SELECT
   `ds_maindata`.*,
   `ds_maindata_regions`.`name` AS `regionname`,
   `ds_maindata_type`.`type_name`,
   `ds_maindata_status`.`status_name`,
   `ds_maindata_platforms`.`platform_url`,
   `ds_maindata_category`.`name` AS `catname`,
   `ds_maindata_hint`.`link` AS `link`,
   `ds_maindata_hint`.`price` AS `price_average`,
   `ds_maindata_hint`.`text` AS `hint`,
   `lot_notes`.`text` AS `note`
    FROM
   `ds_maindata`
    LEFT JOIN `lot_notes` ON `lot_notes`.`lot_id` = `ds_maindata`.`id` AND `lot_notes`.`user_id` = "'.core::$user_id.'"
    LEFT JOIN `ds_maindata_regions` ON `ds_maindata`.`place` = `ds_maindata_regions`.`number`
    LEFT JOIN `ds_maindata_type` ON `ds_maindata`.`type` = `ds_maindata_type`.`id`
    LEFT JOIN `ds_maindata_status` ON `ds_maindata`.`status` = `ds_maindata_status`.`id`
    LEFT JOIN `ds_maindata_platforms` ON `ds_maindata`.`platform_id` = `ds_maindata_platforms`.`id`
    LEFT JOIN `ds_maindata_category` ON `ds_maindata`.`cat_id` = `ds_maindata_category`.`id`
    LEFT JOIN `ds_maindata_hint` ON `ds_maindata`.`id` = `ds_maindata_hint`.`id`
    WHERE `ds_maindata`.`id` = "'.$row['lot_id'].'"
    ;');

    $lot_data =  $lotQuery->fetch_assoc();

    if (mb_strlen($lot_data['name']) > 70){
        $data_a[$i]['name'] = mb_substr($lot_data['name'], 0, 70) . "...";
    } else {
        $data_a[$i]['name'] = $lot_data['name'];
    }
    $data_a[$i]['type'] = ($lot_data['type'] == 1)? "ОА" : "ПП";
    $data_a[$i]['place'] = $lot_data['regionname'];
    $data_a[$i]['status'] = $lot_data['status_name'];
    $data_a[$i]['price'] = intval($lot_data['price']);
    $data_a[$i]['now_price'] = $lot_data['now_price'];
    $data_a[$i]['market_price'] = ($access === true)? $lot_data['now_price'] : $access;

    $i++;
}


$noacc = $access;

$textQuery = core::$db->query('SELECT * FROM ds_pages WHERE id = "16" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();
engine_head($textData['name'], $textData['keywords'], $textData['description']);
temp::assign('title', $textData['name']);
temp::HTMassign('textData', text::out($textData['text'], 0));

temp::HTMassign('noacc', $noacc);
temp::HTMassign('data_m', $data_m);
temp::HTMassign('data_a', $data_a);
temp::display('toplots.index');

engine_fin();
