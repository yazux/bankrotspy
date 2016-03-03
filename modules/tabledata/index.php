<?php
defined('DS_ENGINE') or die('web_demon laughs');

use Foolz\SphinxQL\Connection;
use Foolz\SphinxQL\SphinxQL;

$conn = new Connection();
$conn->setParams(array('host' => '127.0.0.1', 'port' => 9306));



//Ключ, скорее для виду, сесcия доставит гораздо больше проблем =)
$somevar = 'tvybunwedowhduw2397ey9hd8ybhb83wecugwvevct';
if($somevar != POST('somevar'))
    exit('Glory Sithis!');

$category = intval(POST('category'));

$sortcolumn = POST('sortcolumn');
$sorttype = intval(abs(POST('sorttype')));
$tabledata = new tabledata($sortcolumn, $sorttype);
$new_lots = (bool)POST('new_lots');

$first_alt = 0;
$second_alt = 0;

$price_search = 'price';
$type_price = abs(intval(POST('type_price')));

if($type_price == 2) {
    $price_search = 'now_price';
} elseif($type_price == 3) {
    $price_search = 'market_price';
}


$price_start = abs(intval(POST('price_start')));
$price_end = abs(intval(POST('price_end')));

if ($price_start AND $price_end) {
    if ($price_start > $price_end) {
        $price_start = '';
        $price_end  = '';
    }
}

$altint = POST('altint');
if ($altint) {
    $alt_arr = explode('-', $altint);
    $first_alt = isset($alt_arr[0]) ? abs(intval($alt_arr[0])) : 0 ;
    $second_alt = isset($alt_arr[1]) ? abs(intval($alt_arr[1])) : 0 ;
}

$begin_date = abs(intval(POST('begin_date')));
$end_date = abs(intval(POST('end_date')));

if ($end_date AND $begin_date) {
    if ($end_date < $begin_date) {
        $end_date = 0;
        $begin_date = 0;
    }
}
if ($end_date) {
    $end_date = $end_date + ((24*3600)-1); //Добавляем без секунду сутки, чтоб было включительно
}

$types = POST('types');
$types = check_types($types);

$places = POST('places');
$places = check_places($places);

$platforms = POST('platforms');
$platforms = check_platforms($platforms);

$status_need_future = false;
$status_need_now = false;
$status_need_last = false;
$status_item = explode('|', POST('status'));

if (in_array(1, $status_item)) {
    $status_need_future = 1;
}

if (in_array(2, $status_item)) {
    $status_need_now = 1;
}

if (in_array(3, $status_item)) {
    $status_need_last = 1;
}

//echo POST('status').'|'.$status_need_future.'-'.$status_need_now.'-'.$status_need_last;

$svalue = POST('svalue');
if (mb_strlen($svalue) < 3) {
    $svalue = '';
}

//Постраничная навигация
$now_page = abs(intval(POST('page')));
if(!$now_page)
  $now_page = 1;
$kmess = abs(intval(POST('kmess')));
if($kmess > 40)
  $kmess = 40;
$start =  $now_page ? $now_page * $kmess - $kmess : 0;









//Условия для WHERE (компилятся через AND)
$conditions = array();

//Условия для WHERE (компилятся через OR)
$conditions_or = array();

//Дополнительные условия для LEFT JOIN
$join_conditions = array();

//Условия для выборки
$selects = array();

//Условия для сортировки
$order_conditions = array();

//Ностройки сортировки
$sort = $tabledata->get_sort_order();
if($sort)
  $order_conditions['sort'] = $sort;




if ($svalue) {
    /*$query = SphinxQL::create($conn)
                ->select()
                ->from('bs')
                ->match('description', $_POST['svalue']);*/
    $query = SphinxQL::create($conn)->query("SELECT * FROM bs WHERE MATCH('@* ".$svalue."') 
                                       
                                        
                                        LIMIT 100000");
  /*
    if($types)            
        $query->where('type', 'IN', array_flip($types));
    
    //регионы
    if(count(get_places(true)) != count($places) AND $places)            
        $query->where('place', 'IN', array_flip($places));
    //платформы
    if(count(get_platforms(true)) != count($platforms) AND $platforms)            
        $query->where('platform_id', 'IN', array_flip($platforms));
*/
    
    //$before_close = [3, 4, 5, 6];
    //$query->where('status', 'NOT IN', $before_close);
    
    //$query->offset($start)
                //$query->limit('9999999');


    // Массив с полученными результатами
    $result = $query->execute();
    if(!empty($result)) {
        foreach($result as $item) {
            $items[] = $item['id'];
        }
        $items = implode(', ', $items);
        $conditions['search'] = '`ds_maindata`.`id` IN ('.$items.') ';
    } else {
        $conditions['search'] = '`ds_maindata`.`id` IN (0) ';
    }
}

//выборка по категориям
if ($category == '-1') {
    $conditions['fav_sql'] = '`ds_maindata_favorive`.`user_id` = "'.core::$user_id.'" ';
    $join_conditions['fav_sql'] = 'LEFT JOIN `ds_maindata_favorive` ON `ds_maindata`.`id` = `ds_maindata_favorive`.`item` AND `ds_maindata_favorive`.`user_id` = "'.core::$user_id.'"';
    $selects['fav_sql'] = ' `ds_maindata_favorive`.`item` ';
} elseif($category === 5 || $category === 1 || $category === 7 || $category === 6) {
    $conditions['hint'] = ' `ds_maindata`.`cat_id` = "'.$category.'" ';
    $join_conditions['hint']= 'LEFT JOIN `ds_maindata_hint` ON `ds_maindata`.`id` = `ds_maindata_hint`.`id`';
    $selects['hint'] = ' `ds_maindata_hint`.`text` AS hint_text';
} elseif ($category >= 0) {
    $conditions['category'] = ' `ds_maindata`.`cat_id` = "'.$category.'" ';
}

$selects['note'] = ' `lot_notes`.`text` AS note';
$join_conditions['note']= 'LEFT JOIN `lot_notes` ON `lot_notes`.`lot_id` = `ds_maindata`.`id` AND `lot_notes`.`user_id` = "' . core::$user_id.'"';


//Фильтрация по типам
if($types)
  $conditions['types'] = ' `type` IN ('.implode(', ', $types).') ';

//Фильтрация по регионам
if(count(get_places(true)) != count($places) AND $places)
  $conditions['places'] = ' `ds_maindata`.`place` IN ('.implode(', ', $places).') ';

//Фильтрация по платформам
if(count(get_platforms(true)) != count($platforms) AND $platforms)
  $conditions['platforms'] = ' `ds_maindata`.`platform_id` IN ('.implode(', ', $platforms).') ';

//Дата начала и окончания торгов
if(!$first_alt AND !$second_alt)
{
  if($begin_date)
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . $begin_date . '" ';

  if($end_date)
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . $end_date . '" ';
}

if(!$begin_date AND !$end_date)
{
  $nowtime = time();//strtotime(date('Y').'-'.date('n').'-'.date('j'));

  if($first_alt AND $second_alt)
  {
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . ($nowtime + ($first_alt*24*3600)) . '" ';
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . (($nowtime + ($second_alt*24*3600))+((3600*24)-1)) . '" ';
  }
  elseif($first_alt)
  {
    $conditions['starttime'] = ' `ds_maindata`.`start_time` > "' . ($nowtime + ($first_alt*24*3600)) . '" ';
    $conditions['endtime'] = ' `ds_maindata`.`start_time` < "' . (($nowtime + ($first_alt*24*3600))+((3600*24)-1)) . '" ';
  }
}

//Статусы
if($status_need_future AND $status_need_now AND $status_need_last)
{
  //Ничего не делаем, облегчаем работу для базы, выводится все
}
else
{
  $before_close = array(3, 4, 5, 6);
  $edtime = time();//strtotime(date('Y').'-'.date('n').'-'.date('j'));
  if($status_need_future)
    $conditions_or['status_start'] = ' `ds_maindata`.`start_time` > "' . $edtime . '" AND `ds_maindata`.`status` NOT IN ('.implode(', ', $before_close).')';
  if($status_need_now)
    $conditions_or['status_now'] = ' ( `ds_maindata`.`status` NOT IN ('.implode(', ', $before_close).') AND ( `ds_maindata`.`start_time` <= "' . $edtime . '" AND `ds_maindata`.`end_time` >= "' . $edtime . '" ) ) ';
  if($status_need_last)
    $conditions_or['status_last'] = ' ( `ds_maindata`.`status` IN ('.implode(', ', $before_close).') OR `ds_maindata`.`end_time` < "' . $edtime . '") ';
}


if($price_start)
  $conditions['price_start'] = ' `ds_maindata`.`'.$price_search.'` > "' . $price_start . '" ';

if($price_end)
  $conditions['price_end'] = ' `ds_maindata`.`'.$price_search.'` < "' . $price_end . '" ';

if($price_start OR $price_end)
{
  if($type_price == 3)
    $conditions['price_end_third'] = ' `ds_maindata`.`' . $price_search . '` > "0" ';
}
//выборка новых лотов за последние 48 часов
//var_dump($new_lots);
if(!empty($new_lots)) {
    $conditions['loadtime'] = ' FROM_UNIXTIME(`ds_maindata`.`loadtime`) > NOW() - INTERVAL 3 DAY ';
    $order_cond = ' ORDER BY `ds_maindata`.`loadtime` DESC ';
}

//Компилим условия
$where_cond = '';
if($conditions OR $conditions_or)
{
  $where_and = '';
  $where_or = '';
  if($conditions)
    $where_and = '('.implode(' AND ', $conditions).')';
  if($conditions_or)
    $where_or = '('.implode(' OR ', $conditions_or).')';

  $where_cond = ' WHERE '.$where_and.' '.(($where_and AND $where_or) ? ' AND ' : '').' '.$where_or;
}

// Для сортировки по ручному выставлению цены добавим платформу
$join_conditions['platform']= 'LEFT JOIN `ds_maindata_platforms` ON `ds_maindata_platforms`.`id` = `ds_maindata`.`platform_id`';
$selects['platform'] = ' `ds_maindata_platforms`.`manual_price` AS `platform_manual_price`';

// Вычисление падения цены в процентах для выведения значения в колонку и сортировки
$selects['price_diff'] = ' IF(`ds_maindata`.`now_price`>0 AND `ds_maindata`.`price`>0 AND `ds_maindata`.`price`<>`ds_maindata`.`now_price`, ROUND((`ds_maindata`.`price`-`ds_maindata`.`now_price`)/`ds_maindata`.`price`*100, 0), 0) AS `price_diff`';

if($order_conditions) {
    // Если есть выбранные пользователем условия сортировки
    $order_cond = ' ORDER BY ' . implode(' , ', $order_conditions);
} elseif( $category == -2 ) {
    // Если категория "Все", то сортируем по дате
    $order_cond = ' ORDER BY `ds_maindata`.`loadtime` DESC';
} elseif( ($category == 1) || ($category == 3) || ($category == 5) || ($category == 6) || ($category == 7)) {
    // Если категория Авто, Спецтехника, Недвижимость, Зем. участки, то  сортируем по "Доходность, %" от большого к меньшему
    $order_cond = ' ORDER BY (IF(`platform_manual_price`=1 AND `type`=2,1,0)) ASC, (IF(`ds_maindata`.`profit_proc`=0,1,0)) ASC, `ds_maindata`.`profit_proc` DESC';
} else {
    // Сортировка по Падению цены в %
    $order_cond = " ORDER BY (IF(`platform_manual_price`=1 AND `ds_maindata`.`type`=2 AND LENGTH(`ds_maindata`.`grafik1`)<10,1,0)) ASC, (IF(`price_diff`=0,1,0)) ASC, `price_diff` DESC";
}

// Старая версия сортировок
//if($order_conditions && $category == 2) {
//   $order_cond = ' ORDER BY '.implode(' , ', $order_conditions).' , `ds_maindata`.`debpoints` DESC ';
//} elseif($category == 2) { 
//    $order_cond = ' ORDER BY `ds_maindata`.`debpoints` DESC ';
//} elseif($order_conditions) {
//    $order_cond = ' ORDER BY '.implode(' , ', $order_conditions).' , `ds_maindata`.`id` ASC ';
//} else {
//    $order_cond = ' ORDER BY `ds_maindata`.`profit_proc` DESC ';
////$order_cond = ' ORDER BY `ds_maindata`.`id` ASC ';
//}

$join_cond = '';
if($join_conditions)
    $join_cond = ' '.implode(' ', $join_conditions);

$select_cond = '';
if( $selects )
    $select_cond = ' , '.implode(' , ', $selects);

//Счетчик
$count = core::$db->query('SELECT
  COUNT(*)
  FROM
  `ds_maindata`
 '.$join_cond.'

  '.$where_cond.'

  ORDER BY `ds_maindata`.`id`
  ;')->count();

//Основной запрос
$main_sql = 'SELECT
  `ds_maindata`.*

  ' . $select_cond . '
  FROM
  `ds_maindata`
 '.$join_cond.'

  '.$where_cond.'

  ' . $order_cond . '

  LIMIT '.$start.', '.$kmess.' ;';

$res = core::$db->query($main_sql);

//echo core::$db->debugRawQuery();


$item_arr = [];

if($svalue) {
    $item_arr = explode(' ', $svalue);
}

$all_statuses = get_all_status();
$fav_array = get_fav_array();

$out = array();
$out2 = array();

if ($res->num_rows) {
    while ($getdata = $res->fetch_assoc()) {
        $loc = $getdata;
        $loc['status_name'] = $all_statuses[$getdata['status']];
        
        if (in_array($loc['id'], $fav_array)) {
            $loc['item'] = 1;
        } else {
            $loc['item'] = 0;
        }
        $out[] = $loc;
    }

    foreach ($out AS $key => $data) {
        $loc = array();
        $loc['id'] = $data['id'];
        $loc['loadtime'] = $data['loadtime'] * 1000;
        $loc['last_update'] = $data['last_update'] * 1000;
        //$loc['number'] = $tabledata->number($data['code'], $data['id']);
        $loc['name'] = $tabledata->name($data['name'], 80, $data['id'], $item_arr, $data['description'], $data['loadtime']);
        $loc['type'] = $tabledata->type($data['type']);
        $loc['place'] = $tabledata->place($data['place']);
        $loc['begindate'] = $tabledata->begindate($data['start_time']);
        $loc['closedate'] = $tabledata->closedate($data['end_time']);
        $loc['beforedate'] = $tabledata->beforedate($data['start_time'], $data['end_time'], $data['status_name'], $data['status']);
        $loc['beginprice'] = $tabledata->beginprice(round($data['price']));
        $loc['nowprice'] = $tabledata->nowprice($data['now_price'], $data['platform_id'], $data['type'], $data['grafik1']);

        
        
        $access = false;
        $vipAccess = false;
        
        if (in_array(core::$rights, [10,11,100])) {
            $access = true;
        }
        
        if(CAN('scores_debtor')) {
            $vipAccess = true;
        }
        /*
            0 - ? 
            2 - Деб. задолженность
            4 - Сельхоз. имущество
            5 - Недвиж. жилая
            8 - Обор. Инст. Мат.
        */
        if ($category != 0 & $category != 4 & $category != 8 & $category != 2) {
            if ($category === 5 || $category === 1 || $category === 7 || $category === 6) {
                if($vipAccess == false) {
                    $data['hint_text'] = 'Информация доступна на тарифном плане VIP';
                }
                if (CAN('get_lot_price') && empty($data['market_price'])) {
                    $loc['marketprice'] = $tabledata->marketprice($data['market_price'], $access, $data['hint_text'], true);
                } else {
                    $loc['marketprice'] = $tabledata->marketprice($data['market_price'], $access, $data['hint_text']);
                }
            } else {
                
                if (CAN('get_lot_price') && empty($data['market_price'])) {
                    $loc['marketprice'] = $tabledata->marketprice($data['market_price'], true, '', true);
                } else {
                    $loc['marketprice'] = $tabledata->marketprice($data['market_price'], $access);
                }
            }
            
            // расчет прибыли в рублях и в процентах
            /*
            if($data['now_price'] > 0 && $data['market_price'] > 0) {
                $data['profit_rub'] = $data['market_price'] - $data['now_price'];
                
                $percent = ($data['profit_rub'] / $data['market_price']) * 100;
                
                $data['profit_proc'] = floor($percent); 
            }*/
            
            
            $loc['profitrub'] = $tabledata->profitrub($data['profit_rub'], $data['platform_id'], $data['type'], $access, $data['grafik1']);
            $loc['profitproc'] = $tabledata->profitproc($data['profit_proc'], $data['platform_id'], $data['type'], $access, $data['grafik1']);
        }
        //elseif($category == 0 OR $category == 4 OR $category == 8)
        //{
       
      
        // разница между текущей и начальной ценой
//        if($data['now_price'] > 0 && $data['price'] > 0 && $data['price'] != $data['now_price']) {   
//        
//            $percent = ($data['price'] - $data['now_price']) / $data['price'] * 100;
//            $data['price_dif'] = floor($percent); 
//            
//        }
        
        $loc['pricediff'] = $tabledata->pricediff($data['price_diff'], $data['platform_id'], $data['type'], $data['grafik1']);
        //}
        if ($category == 2) {
            //$loc['pricediff'] = $tabledata->pricediff($data['price_dif'], $data['platform_id'], $data['type']);
            $loc['debpoints'] = $tabledata->debpoints($data['debpoints'], $data['debnotice'], $vipAccess);
        }

        $loc['platform'] = $tabledata->platform($data['platform_id'], $data['auct_link'], $data['fedlink'], $access);
        //var_dump($data);
        
        //$loc['favorite'] = $tabledata->favorite($data['id'], $data['item']);
        //var_dump($data);
        $loc['favorite'] = $tabledata->addition($data['id'], $data['item'], $data['note'], $category);
        $out2[] = $loc;
    }

    $out = $out2;

    $outdata = array(
        'columns' => $tabledata->get_names(),
        'maindata' => $out,
        'count' => $count
    );
} else {
    $outdata = array(
        'columns' => array('name' => array(
            'name' => (($category == '-1' AND !core::$user_id) ? 'Добавление в избранное доступно только зарегистрированным пользователям!' : 'Ничего не найдено!'),
            'style' => 'background:white;border:0px;padding: 40px 0;font-size: 14px;'
        )),
        'maindata' => '',
        'count' => $count
    );
}

function check_platforms($platforms) {
    $in_platforms = get_platforms();
    $arr = explode('|', $platforms);
    $out = array();
    foreach ($arr AS $val) {
        if(isset($in_platforms[$val]))
            $out[] = $val;
    }
    if (!$out) {
        return get_platforms(true);
    } else {
        return $out;
    }
}

function get_platforms($only_keys = false)
{
  $platforms = array();
  $out = array();
  if(!rem::exists('maintable_platforms'))
  {
    $res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['id'];
      $platforms[$data['id']] = $data['platform_url'];
    }
    rem::remember('maintable_platforms', serialize($platforms));
    rem::remember('maintable_platforms_out', serialize($out));
  }
  else
  {
    $platforms = unserialize(rem::get('maintable_platforms'));
    $out = unserialize(rem::get('maintable_platforms_out'));
  }

  if($only_keys)
    return $out;
  else
    return $platforms;
}

function check_places($places)
{
  $in_places = get_places();
  $arr = explode('|', $places);
  $out = array();
  foreach($arr AS $val)
  {
    if(isset($in_places[$val]))
      $out[] = $val;
  }
  if(!$out)
    return get_places(true);
  else
    return $out;
}

function get_places($only_keys = false)
{
  $places = array();
  $out = array();
  if(!rem::exists('maintable_places'))
  {
    $res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['number'];
      $places[$data['number']] = $data['name'];
    }
    rem::remember('maintable_places', serialize($places));
    rem::remember('maintable_places_out', serialize($out));
  }
  else
  {
    $places = unserialize(rem::get('maintable_places'));
    $out = unserialize(rem::get('maintable_places_out'));
  }

  if($only_keys)
    return $out;
  else
    return $places;
}

function check_types($types)
{
  $in_types = get_types();
  $arr = explode('|', $types);
  $out = array();
  foreach($arr AS $val)
  {
    if(isset($in_types[$val]))
      $out[] = $val;
  }
  if(!$out)
    return get_types(true);
  else
    return $out;
}

function get_types($only_keys = false)
{
  $types = array();
  $out = array();
  if(!rem::exists('maintable_types'))
  {
    $res = core::$db->query('SELECT * FROM  `ds_maindata_type` ;');
    while($data = $res->fetch_array())
    {
      $out[] = $data['id'];
      $types[$data['id']] = text::st($data['type_name']);
    }
    rem::remember('maintable_types', serialize($types));
    rem::remember('maintable_types_out', serialize($out));
  }
  else
  {
    $types = unserialize(rem::get('maintable_types'));
    $out = unserialize(rem::get('maintable_types_out'));
  }

  if($only_keys)
    return $out;
  else
    return $types;
}

function get_all_status()
{
  $out = array();
  $req = core::$db->query('SELECT * FROM `ds_maindata_status` ;');
  while($data = $req->fetch_assoc())
  {
    $out[$data['id']] = $data['status_name'];
  }

  return $out;
}

function get_fav_array()
{
  $out = array();
  if(core::$user_id)
  {
    $req = core::$db->query('SELECT * FROM `ds_maindata_favorive` WHERE `user_id` = "'.core::$user_id.'" ;');
    while($data = $req->fetch_assoc())
    {
      $out[] = $data['item'];
    }
  }
  return $out;
}

echo json_encode($outdata);