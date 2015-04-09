<?php
defined('DS_ENGINE') or die('web_demon laughs');

$order_id = $_GET['orderId'];

$ord_arr = explode('-', $order_id);
$ord_arr[1] = abs(intval($ord_arr[1]));
$ord_arr[2] = abs(intval($ord_arr[2]));
$ord_arr[3] = abs(intval($ord_arr[3]));
$tar_array = array(1, 3, 12);
$order_id = $ord_arr[0].'-'.$ord_arr[1].'-'.$ord_arr[2].'-'.$ord_arr[3];

if(count($ord_arr) == 4 AND $ord_arr[0] == core::$set['market_prefix'] AND user::exists_id($ord_arr[1]) AND in_array($ord_arr[2], $tar_array))
{
  $req = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.$ord_arr[1].'"');
  $data = $req->fetch_assoc();

  $check = merchant_api("checkPayment", array("marketPlace" => $market_place_id, "orderId" => $order_id));

  if($check['status'] === 'STATUS_SUCCESS')
  {
    engine_head(lang('tariffs'));
    temp::display('tariffs.waitforpay');
    engine_fin();
  }
  else
  {
    if(core::$user_id)
    {
      new mail_temp('./data/engine/');
      mail_temp::assign('home', core::$home);
      mail_temp::assign('orderid', $order_id);
      mail_temp::assign('userid', $ord_arr[1]);
      mail_temp::assign('details', @$check['errorCode'].' / '.@$check['errorDescription'].' / '.$check['payment']['state']);
      $mail_body = mail_temp::get('mail_buy_err');
      //smtp::mail('analytic-spy@i-tt.ru', ' ', lang('mail_head_err').' '.$order_id.' ('.core::$set['site_name_main'].')', $mail_body);
    }

    engine_head(lang('tariffs'));
    temp::assign('orderid', @$order_id);
    temp::assign('error_code', @$check['errorCode'].' / '.@$check['payment']['state']);
    temp::assign('error_description', @$check['errorDescription']);
    temp::display('tariffs.bad');
    engine_fin();
  }
}
else
  echo denied();