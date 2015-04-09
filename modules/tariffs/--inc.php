<?php
defined('DS_ENGINE') or die('web_demon laughs');

$merchant_key = core::$set['merchant_key'];
// UID магазина
$market_place_id = core::$set['market_id'];
// Адрес платёжной страницы
$oos_payment_page = "https://oos.pscb.ru/pay/";

$merchant_api_url_base = "https://oos.pscb.ru/merchantApi";

function merchant_api($method, $params)
{
  global $merchant_key, $merchant_api_url_base;
  $url = "$merchant_api_url_base/$method";
  $request_body = json_encode($params);
  $raw_signature = $request_body . $merchant_key;
  $signa = hash('sha256', $raw_signature);
  $request_headers = array(
    "Signature: " . $signa,
    "Expect: ",
    "Content-Type: application/json",
    "Content-Length: " . strlen($request_body),
  );
  static $curl = null;
  if (is_null($curl)) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; OOS API client; '.php_uname('s').'; PHP/'.phpversion().')');
  }
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_POST, true);
  curl_setopt($curl, CURLOPT_POSTFIELDS, $request_body);
  curl_setopt($curl, CURLOPT_HTTPHEADER, $request_headers);
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $response_text = curl_exec($curl);
  if ($response_text === false) {
    throw new Exception('Could not get reply: '.curl_error($curl));
  }
  $response_json = json_decode($response_text, true);
  if (!$response_json) {
    throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
  }
  return $response_json;
}


function check_user_paytime($id, $time)
{
  $req = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.core::$db->res($id).'"');
  if($req->num_rows AND $time)
  {
    $data = $req->fetch_assoc();
    if($data['ordertimeid'] == $time)
      return TRUE;
    else
      return FALSE;
  }
  else
    return FALSE;
}