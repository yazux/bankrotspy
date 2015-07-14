<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(GET('id')));
if(!$id)
  denied();


$twr = core::$db->query('SELECT
   `ds_maindata`.*,
   `ds_maindata_regions`.`name` AS `regionname`,
   `ds_maindata_type`.`type_name`,
   `ds_maindata_status`.`status_name`,
   `ds_maindata_platforms`.`platform_url`,
   `ds_maindata_category`.`name` AS `catname`
    FROM
   `ds_maindata`
    LEFT JOIN `ds_maindata_regions` ON `ds_maindata`.`place` = `ds_maindata_regions`.`number`
    LEFT JOIN `ds_maindata_type` ON `ds_maindata`.`type` = `ds_maindata_type`.`id`
    LEFT JOIN `ds_maindata_status` ON `ds_maindata`.`status` = `ds_maindata_status`.`id`
    LEFT JOIN `ds_maindata_platforms` ON `ds_maindata`.`platform_id` = `ds_maindata_platforms`.`id`
    LEFT JOIN `ds_maindata_category` ON `ds_maindata`.`cat_id` = `ds_maindata_category`.`id`
    WHERE `ds_maindata`.`id` = "'.$id.'"
    ;');
if(!$twr->num_rows)
  denied();

$data = $twr->fetch_assoc();

$in_favorite = 0;
if(core::$user_id)
{
  $res = core::$db->query('SELECT COUNT(*) FROM `ds_maindata_favorive` WHERE `item` = "' . $id . '" AND `user_id` = "' . core::$user_id . '" ')->count();
  if($res > 0)
    $in_favorite = 1;
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

$nowprice = $tabledata->nowprice($data['now_price'], $data['platform_id']);
$nowprice = $nowprice['col'];

$status = $tabledata->beforedate($data['start_time'], $data['end_time'], $data['status_name'], $data['status']);
$status = $status['col'];

$pricediff = $tabledata->pricediff($data['price_dif'], $data['platform_id']);
$pricediff = $pricediff['col'];
if($pricediff > 0)
  $pricediff = '-'.$pricediff;
elseif($pricediff < 0)
  $pricediff = '+'.abs($pricedif);

if($data['cat_id'] != 0 AND $data['cat_id'] != 4 AND $data['cat_id'] != 8 AND $data['cat_id'] != 2)
{
  $needshow_add_price = 1;
  $realprice = $tabledata->marketprice($data['market_price']);
  $realprice = $realprice['col'];
  $profitrub = $tabledata->profitrub($data['profit_rub'],  $data['platform_id']);
  $profitrub = $profitrub['col'];
  $profitproc = $tabledata->profitproc($data['profit_proc'], $data['platform_id']);
  $profitproc = $profitproc['notcolored'];
}
if($data['cat_id'] == 2)
{
  $needshow_deb_points = 1;
  $debpoints = $tabledata->debpoints($data['debpoints'], $data['debnotice']);

  if(isset($debpoints['addition']) AND $debpoints['addition'])
    $additionhtmldeb = $debpoints['addition'];

  if(isset($debpoints['customclass']) AND $debpoints['customclass'])
    $customclassdeb = $debpoints['customclass'];

  $debpoints = $debpoints['col'];
}

//Выводим страничку
core::$page_description = mb_substr($data['name'], 0, 200);
engine_head(lang('card_n').''.$id);

if(isset($additionhtmldeb))
  temp::HTMassign('additionhtmldeb', $additionhtmldeb);
if(isset($customclassdeb))
  temp::HTMassign('customclassdeb', ' class="'.$customclassdeb.'" ');

temp::assign('id', $data['id']);
temp::assign('category', $data['catname']);
temp::HTMassign('lotdescr', $lotname);
if(isset($needshow_add_price))
{
  temp::assign('needshow_add_price', $needshow_add_price);
  temp::HTMassign('realprice', $realprice);
  temp::HTMassign('profitrub', $profitrub);
  temp::HTMassign('profitproc', $profitproc);
}
if(isset($needshow_deb_points))
{
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
temp::assign('platform_url', $data['platform_url']);
temp::assign('auct_link', $data['auct_link']);
temp::assign('code_torg', $data['code']);

if(isset($data_debt['dept_name']))
{
  temp::assign('debtor', $data_debt['dept_name']);
  temp::assign('debtor_inn', $data_debt['inn']);
  if($data_debt['debt_profile'] AND $data_debt['debt_profile'] != 'www1')
    temp::assign('debtor_profile', $data_debt['debt_profile']);
}

if(isset($data_org['org_name']))
{
  temp::assign('organizer', $data_org['org_name']);
  temp::assign('contact_person', $data_org['contact_person']);
  temp::assign('manager', $data_org['manager']);
  temp::assign('inn_orgname', $data_org['inn']);
  if(isset($data_org['org_profile']) AND $data_org['org_profile'])
    temp::assign('organizer_profile', $data_org['org_profile']);
  if(isset($data_org['arbitr_profile']) AND $data_org['arbitr_profile'])
    temp::assign('arbitr_profile', $data_org['arbitr_profile']);
}

temp::assign('case_number', $data['case_number']);
temp::HTMassign('nowprice', $nowprice);
temp::assign('lotnumber', $data['code']);
temp::assign('lotfav', $in_favorite);
temp::display('cards.index');
engine_fin();