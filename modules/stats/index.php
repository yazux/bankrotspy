<?php

defined('DS_ENGINE') or die('web_demon laughs');


$current_time = time();
$where = "status = 1";

// Общее кол-во записей
$cntQuery = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE ".$where);
$total = $cntQuery->fetch_assoc()['cnt'];

// информация
$data = [];
// названия
$sortNames = [];

// По категории
$data[0] = [];
$sortNames[0] = [];
$categories = core::$db->query("SELECT * FROM ds_maindata_category");
$sortNames[0][0] = 'Прочее';

while ($cat = $categories->fetch_assoc()) {
    $sortNames[0][$cat['id']] = $cat['name'];
}

for ($i = 0; $i < count($sortNames[0]); $i++) {
    $query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE cat_id = '".$i."' AND ".$where);
    $data[0][$i] = $query->fetch_assoc()['cnt'];
}


// По цене
$sortNames[1] = [];
$sortNames[1][0] = 'до 20 т.р.';
$sortNames[1][1] = '20–200 т.р.';
$sortNames[1][2] = '200–1000 т.р.';
$sortNames[1][3] = '1000–10 000 т.р.';
$sortNames[1][4] = 'более 10 000т.р.';

$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE price < '20000' AND ".$where);
$data[1][0] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE price > '20000' AND price < '200000' AND ".$where);
$data[1][1] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE price > '200000' AND price < '1000000' AND ".$where);
$data[1][2] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE price > '1000000' AND price < '10000000' AND ".$where);
$data[1][3] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE price > '10000000' AND ".$where);
$data[1][4] = $query->fetch_assoc()['cnt'];


// По доходности
$sortNames[2] = [];;
$sortNames[2][0] = '+2000%';
$sortNames[2][1] = '+2000%–100%';
$sortNames[2][2] = '+100%-50%';
$sortNames[2][3] = '+50%-0%';
$sortNames[2][4] = 'отрицательная';

$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE profit_proc >= '2000' AND ".$where);
$data[2][0] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE profit_proc < '2000' AND profit_proc > '100' AND ".$where);
$data[2][1] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE profit_proc < '100' AND profit_proc > '50' AND ".$where);
$data[2][2] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE profit_proc < '50' AND profit_proc >= '0' AND ".$where);
$data[2][3] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE profit_proc < '0' AND ".$where);
$data[2][4] = $query->fetch_assoc()['cnt'];


// По типу торгов
$query = core::$db->query("SELECT type, COUNT( id ) AS cnt FROM ds_maindata WHERE ".$where." GROUP BY type");
$i = 0;
while($row = $query->fetch_assoc()) {
    $data[3][$i] = $row['cnt'];
    $query_name = core::$db->query("SELECT type_name FROM ds_maindata_type WHERE id = ".$row['type']." LIMIT 1");
    $query_name = $query_name->fetch_assoc();
    $sortNames[3][$i] = $query_name['type_name'];
    $i++;
}


// По времени торгов
$sortNames[4] = [];
$sortNames[4][0] = '0-10 дн.';
$sortNames[4][1] = '10-50 дн.';
$sortNames[4][2] = '50-200 дн.';
$sortNames[4][3] = 'более 200 дн.';

$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE ".$current_time." - start_time < '864000' AND ".$where);
$data[4][0] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE ".$current_time." - start_time > '864000' AND ".$current_time." - start_time < '4320000' AND ".$where);
$data[4][1] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE ".$current_time." - start_time > '4330000' AND ".$current_time." - start_time < '17280000' AND ".$where);
$data[4][2] = $query->fetch_assoc()['cnt'];
$query = core::$db->query("SELECT COUNT(*) AS cnt FROM ds_maindata WHERE ".$current_time." - start_time > '17280000' AND ".$where);
$data[4][3] = $query->fetch_assoc()['cnt'];

// Топ городов
$query = core::$db->query("SELECT place, COUNT(id) as cnt FROM ds_maindata WHERE ".$where." GROUP BY place ORDER BY cnt desc LIMIT 10");
$i = 0;
while($row = $query->fetch_assoc()) {
    $data[5][$i] = $row['cnt'];
    $query_name = core::$db->query("SELECT name FROM ds_maindata_regions WHERE id = ".$row['place']." LIMIT 1");
    $query_name = $query_name->fetch_assoc();
    $sortNames[5][$i] = $query_name['name'];
    $i++;
}

// получение процентов
$percent = $total/100;
for ($i = 0; $i < count($sortNames); $i++) {
    for ($j = 0; $j < count($sortNames[$i]); $j++) {
        $sortNames[$i][$j] = $sortNames[$i][$j]." (".round($data[$i][$j]/$percent, 2)."%)";
    };
};

$datajs = json_encode($data, true);
$sortNamesjs = json_encode($sortNames, true);

$textQuery = core::$db->query("SELECT * FROM ds_pages WHERE id = '14' LIMIT 1");
$textData =  $textQuery->fetch_assoc();
engine_head($textData['name'], $textData['keywords'], $textData['description']);
temp::assign('title', $textData['name']);

temp::assign('total', $total);
temp::HTMassign('textData', text::out($textData['text'], 0));
temp::HTMassign('navigation', nav::display($total, core::$home.'/stats/?'));
temp::HTMassign('sortNames', $sortNames);
temp::HTMassign('sortNamesjs', $sortNamesjs);
temp::HTMassign('data', $data);
temp::HTMassign('datajs', $datajs);

temp::display('stats.index');

engine_fin();
