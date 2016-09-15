<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(GET('id')));
if(!$id)
  denied();

$access = false;
$realPriceIsNumeric = false;
        
if(in_array(core::$rights, [10,11,100])) {
    $access = true;
}

$twr = core::$db->query('SELECT
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
    WHERE `ds_maindata`.`id` = "'.$id.'"
    ;');

if(!$twr->num_rows)
    denied();

$data = $twr->fetch_assoc();

//тип графика (техника)
$graphType = 1;

// если категория недвижимость берем среднее метр квадратный
if(in_array($data['cat_id'], [5,6])) {
    $m2price = $query = core::$db->query('SELECT * FROM `ds_maindata_hint` WHERE `id` = "' . $id . '" LIMIT 1');
    $average = $m2price->fetch_assoc();
    $graphType = 2;//недвижимость
}

$query = core::$db->query('SELECT * FROM `lot_prices` WHERE `id` = "' . $id . '" ORDER BY price ASC');
$countLot = $query->num_rows;
$similarDataPrice = array();

// надо минимум 2 значения для построения графика
if($countLot > 1 ) { 
    while($row = $query->fetch_assoc()) {
        // если категория недвижимость берем среднее метр квадратный иначе рыночная стоимость
        if($data['cat_id'] == 5 || $data['cat_id'] == 6) {
            $row['average'] = $average['price'];
        } else {
            $row['average'] = $data['market_price'];
        }
        $similarDataPrice[] = $row;
    }
}

// Получение фотографий лота если есть
$fotos = null;
$req = core::$db->query('SELECT * FROM `lot_fotos` WHERE `lotid` = "'.core::$db->res($data['id']).'"');
if( $req->num_rows ) {
    while( $foto = $req->fetch_assoc() ) {
        $fotos[] = 'http://foto.bankrot-spy.ru' . $foto['link'];
    }
}

$in_favorite = 0;
if( core::$user_id ) {
    $res = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_favorive` WHERE `item` = "' . $id . '" AND `user_id` = "' . core::$user_id . '" ')->count();
    if($res > 0)
        $in_favorite = 1;
}

$in_hide = 0;
if( core::$user_id ) {
    $res = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_hide` WHERE `item` = "' . $id . '" AND `user_id` = "' . core::$user_id . '" ')->count();
    if($res > 0)
        $in_hide = 1;
}

$in_complaint = 0;
if( core::$user_id ) {
    $res = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_complaint` WHERE `item` = "' . $id . '" AND `user_id` = "' . core::$user_id . '" ')->count();
    if($res > 0)
        $in_complaint = 1;
}

$req = core::$db->query('SELECT * FROM `ds_maindata_debtors` WHERE `id` = "'.core::$db->res($data['debtor']).'"');
if($req->num_rows)
    $data_debt = $req->fetch_assoc();

$req = core::$db->query('SELECT * FROM `ds_maindata_organizers` WHERE `id` = "'.core::$db->res($data['organizer']).'"');
if($req->num_rows)
  $data_org = $req->fetch_assoc();

//Получаем список классов
$tabledata = new tabledata(0, 0);

//Текущая цена
$lotprice = $tabledata->beginprice($data['price']);
$lotprice = $lotprice['col'];

$lotname = $tabledata->name($data['name'], 40, $data['id'], array(), $data['description']);
$lotname = $lotname['onlydata'];

$nowprice = $tabledata->nowprice($data['now_price'], $data['platform_id'], $data['type'], $data['grafik1'], $data['calc_n_time']);
//var_dump($nowprice);//die();
$isCalculated = $nowprice['isCalculated'];
$nowprice = $nowprice['col'];

$status = $tabledata->beforedate($data['start_time'], $data['end_time'], $data['status_name'], $data['status']);
$status = $status['col'];

$pricediff = $tabledata->pricediff($data['price_dif'], $data['platform_id'], $data['type'], $data['grafik1']);
$pricediff = $pricediff['col'];
if($pricediff > 0)
    $pricediff = '-'.$pricediff;
elseif($pricediff < 0)
    $pricediff = '+'.abs($pricedif);

if($data['cat_id'] != 0 AND $data['cat_id'] != 4 AND $data['cat_id'] != 8 AND $data['cat_id'] != 2) {
    
    // расчет профита в рублях и в процентах
    /*
    if($data['now_price'] > 0 && $data['market_price'] > 0) {
        $data['profit_rub'] = $data['market_price'] - $data['now_price'];
        $percent = ($data['profit_rub'] / $data['market_price']) * 100;
        $data['profit_proc'] = floor($percent); 
    }*/
    
    $needshow_add_price = 1;
    $realprice = $tabledata->marketprice($data['market_price'], $access);

    $realPriceIsNumeric = $realprice['isNumeric'];
    //var_dump($realprice);
    $realprice = $realprice['col'];
  
    $profitrub = $tabledata->profitrub($data['profit_rub'],  $data['platform_id'], $data['type'], $access, $data['grafik1']);
    $profitrub = $profitrub['col'];
    $profitproc = $tabledata->profitproc($data['profit_proc'], $data['platform_id'], $data['type'], $access, $data['grafik1']);

    $profitproc = $profitproc['notcolored'];
}

// стоимость м.кв и ссылка


if ($data['cat_id'] == 2) {
    
    $vipAccess = false;
    
    if(CAN('scores_debtor')) {
        $vipAccess = true;
    }
    
    $needshow_deb_points = 1;
    $debpoints = $tabledata->debpoints($data['debpoints'], $data['debnotice'], $vipAccess);

    if (isset($debpoints['addition']) AND $debpoints['addition']) {
        $additionhtmldeb = $debpoints['addition'];
    }

    if (isset($debpoints['customclass']) AND $debpoints['customclass']) {
        $customclassdeb = $debpoints['customclass'];
    }

  $debpoints = $debpoints['col'];
}

//Выводим страничку
core::$page_description = mb_substr($data['name'], 0, 200);
engine_head(lang('card_n').''.$id);

if(isset($additionhtmldeb))
    temp::HTMassign('additionhtmldeb', $additionhtmldeb);
if(isset($customclassdeb))
    temp::HTMassign('customclassdeb', ' class="'.$customclassdeb.'" ');


if(in_array($data['cat_id'], [1,5,6,7])) {
    $field = '';

    $fields = array(
        '1' => 'Средняя цена на открытом рынке:',
        '5' => 'Средняя цена м.кв на открытом рынке:',
        '6' => 'Средняя цена м.кв на открытом рынке:',
        '7' => 'Средняя цена на открытом рынке:',
    );
    //var_dump(time() + 1*24*3600);
    //var_dump(core::$rights);
    //var_dump(CAN('cost_meter'));
    
    if (!empty($data['hint'])) {
        if(CAN('cost_meter')) {
            //var_dump($data);
            $priceHint = !empty($data['hint']) ? $data['hint'] : '';
            //str_replace('&amp;nbsp;', ' ', $data['hint'])
            $field = '
                <td style="width: 200px;"><b>'.$fields[$data['cat_id']].'</b><br/></td>
                <td><i class="icon-rouble"></i> <a href="'.$data['link'].'" target="_blank">'.$priceHint.'</a></td>';

        } else {
            $field = '<td style="width: 200px;"><b>'.$fields[$data['cat_id']].'</b><br/></td>
                    <td onmouseout="toolTip()" onmouseover="toolTip(\'Данная функция доступна на тарифном плане VIP\')"><i class="icon-rouble"></i> <i class="fa fa-lock"></i></td>';
        }
        temp::HTMassign('market_price', $field);    
    }
}


if(in_array($data['platform_id'], [24,28,34,35,37,40,41,49]) && $data['type'] == 2 && strlen($data['grafik1']) > 10) {
    //temp::HTMassign('schedule', $data['grafik1']);
}

temp::HTMassign('note', $data['note']);

temp::assign('id', $data['id']);
temp::assign('category', $data['catname']);
temp::assign('categoryId', $data['cat_id']);
temp::HTMassign('lotdescr', $lotname);
if(isset($needshow_add_price)) {
    temp::assign('needshow_add_price', $needshow_add_price);
    temp::HTMassign('realprice', $realprice);
    temp::HTMassign('realPriceIsNumeric', $realPriceIsNumeric);
    temp::HTMassign('profitrub', $profitrub);
    temp::HTMassign('profitproc', $profitproc);
}
if(isset($needshow_deb_points)) {
    temp::assign('needshow_deb_points', $needshow_deb_points);
    temp::HTMassign('debpoints', $debpoints);
}
temp::HTMassign('pricediff', $pricediff);
temp::assign('lotregion', $data['regionname']);
temp::assign('lottype', $data['type_name']);
temp::assign('lotstatus', $status);
temp::assign('lotstarttime', ds_time($data['start_time']));
temp::assign('lotendtime', ds_time($data['end_time']));
temp::HTMassign('lotprice', $lotprice);

if($access) {
    $platform_url = $data['platform_url'];
    $fedlink_url = $data['fedlink'];
    $auct_url  = $data['auct_link'];
    $code_torg = $data['code'];
} else {
    $platform_url = '';
    $fedlink_url = -1;
    $auct_url = -1;
    $code_torg = '-1';
}

temp::assign('platform_url', $platform_url);
temp::assign('fedlink', $fedlink_url);


temp::assign('auct_link', $auct_url);
temp::assign('code_torg', $code_torg);

if(!empty($similarDataPrice) && CAN('histogram_goods')) {
    temp::HTMassign('similarDataPrice', json_encode($similarDataPrice));
} elseif(!empty($similarDataPrice) && !CAN('histogram_goods')) {
    temp::HTMassign('similarDataPrice', 'access');
}

temp::assign('graphType', $graphType);

if(isset($data_debt['dept_name']))
{
  temp::assign('debtor', $data_debt['dept_name']);
  temp::assign('debtor_inn', $data_debt['inn']);
  if($data_debt['debt_profile'] AND $data_debt['debt_profile'] != 'www1')
    temp::assign('debtor_profile', $data_debt['debt_profile']);
}

if($access) {
    $contact_person_phone = $data_org['phone'];
    $contact_person_email = $data_org['mail'];
} else {
    $contact_person_phone = $data_org['phone'] = -1;
    $contact_person_email = $data_org['email'] = -1;
}

if (isset($data_org['org_name'])) {
    temp::assign('organizer', $data_org['org_name']);
    temp::assign('contact_person', $data_org['contact_person']);
    temp::assign('contact_person_phone', $contact_person_phone);
    temp::assign('contact_person_email', $contact_person_email);
    temp::assign('manager', $data_org['manager']);
    temp::assign('inn_orgname', $data_org['inn']);
  
    temp::assign('oid', $data_org['id']);
    if ($data_org['totaldoc'] > 0) {
        temp::assign('rating', $data_org['bal']); // рейтинг
        temp::assign('docs', $data_org['linkdocs']); // ссылка на документы
    } else {
        temp::assign('rating', 'Нет данных'); // рейтинг
        temp::assign('docs', 'Нет данных'); // ссылка на документы
    }
    
    if (isset($data_org['org_profile']) && $data_org['org_profile']) {
        temp::assign('organizer_profile', $data_org['org_profile']);
    }
  
    if (isset($data_org['arbitr_profile']) && $data_org['arbitr_profile']) {
        temp::assign('arbitr_profile', $data_org['arbitr_profile']);
    }
}

if ( $fotos ) {
    temp::HTMassign('fotos', $fotos);
}

if ($data['status'] == 1 || $data['status'] == 2){
    core::$db->query('INSERT INTO `lot_views` SET
       `lot_id` = "'.$id.'",
       `view_date` = "'.time().'"
       ;');
}



temp::assign('case_number', $data['case_number']);
temp::assign('reportLink', $data['reportlink']);
temp::HTMassign('nowprice', $nowprice);
temp::assign('isCalculated', $isCalculated);
temp::assign('lotnumber', $data['code']);
temp::assign('lotfav', $in_favorite);
temp::assign('lothide', $in_hide);
temp::assign('lotcomplaint', $in_complaint);
temp::display('cards.index');
engine_fin();