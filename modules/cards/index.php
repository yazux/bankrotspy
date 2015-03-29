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
   `ds_maindata_platforms`.`platform_url`
    FROM
   `ds_maindata`
    LEFT JOIN `ds_maindata_regions` ON `ds_maindata`.`place` = `ds_maindata_regions`.`number`
    LEFT JOIN `ds_maindata_type` ON `ds_maindata`.`type` = `ds_maindata_type`.`id`
    LEFT JOIN `ds_maindata_status` ON `ds_maindata`.`status` = `ds_maindata_status`.`id`
    LEFT JOIN `ds_maindata_platforms` ON `ds_maindata`.`platform_id` = `ds_maindata_platforms`.`id`
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

function out_price($price)
{
  $price = strrev($price);
  $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
  $out_price = '';

  $i = 1;
  foreach($chars AS $val)
  {
    $out_price .= $val;
    if($i == 3)
    {
      $out_price .= ' '; //Неразрывный пробел наоборот
      $i = 0;
    }
    $i++;
  }
  return strrev($out_price);
}

core::$page_description = mb_substr($data['name'], 0, 200);
engine_head(lang('card_n').''.$id);

temp::assign('lotname', $data['name']);
temp::assign('id', $data['id']);
temp::assign('lotdescr', $data['description']);
temp::assign('lotregion', $data['regionname']);
temp::assign('lottype', $data['type_name']);
temp::assign('lotstatus', $data['status_name']);
temp::assign('lotstarttime', ds_time($data['start_time']));
temp::assign('lotendtime', ds_time($data['end_time']));
temp::assign('lotprice', out_price($data['price']));
temp::assign('platform_url', $data['platform_url']);
temp::assign('auct_link', $data['auct_link']);
temp::assign('code_torg', $data['code']);
temp::assign('debtor', $data['debtor']);
temp::assign('case_number', $data['case_number']);
temp::assign('organizer', $data['organizer']);
temp::assign('contact_person', $data['contact_person']);
temp::assign('contact_person', $data['contact_person']);
temp::assign('manager', $data['manager']);
temp::assign('nowprice', out_price($data['now_price']));
temp::assign('lotnumber', $data['code']);
temp::assign('lotfav', $in_favorite);
temp::display('cards.index');
engine_fin();