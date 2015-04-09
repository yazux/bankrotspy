<?php
defined('DS_ENGINE') or die('web_demon laughs');

function decrypt_aes128_ecb_pkcs5($encrypted, $merchant_key)
{
  $key_md5_binary = hash("md5", $merchant_key, true);
  $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key_md5_binary, $encrypted, MCRYPT_MODE_ECB);
  $padSize = ord(substr($decrypted, -1));
  return substr($decrypted, 0, $padSize*-1);
}

$encrypted_request = file_get_contents('php://input');
$decrypted_request = decrypt_aes128_ecb_pkcs5($encrypted_request, $merchant_key);

//Потом убрать
file_put_contents('data/pscb_req/req_'.time().'.txt', $decrypted_request);

if($decrypted_request)
  $json_request = json_decode($decrypted_request, 1);

if(!isset($json_request)){
  header('HTTP/1.1 401 Unauthorized');
  exit();
}

if(!isset($json_request['payments']))
{
  header('HTTP/1.1 503 Service Temporarily Unavailable');
  exit();
}

$rej_array = array('exp', 'err', 'rej', 'ref');
$answer_arr = array();
$answer_arr['payments'] = array();

$tar_array = array();
$tar_summ = array();
$tar_tariff = array();
$tar_rights = array();
$tar_time = array();
$res = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');
while($data = $res->fetch_array())
{
  $tar_array[] = $data['id'];
  $tar_summ[$data['id']] = $data['price'];
  $tar_tariff[$data['id']] = $data['name'];
  $tar_rights[$data['id']] = $data['rights'];
  $tar_time[$data['id']] = $data['longtime'];
}

foreach($json_request['payments'] AS $key => $val)
{
    if($val['state'] === 'end')
    {
      $order_id = $val['orderId'];
      $ord_arr = explode('-', $order_id);
      $ord_arr[1] = abs(intval($ord_arr[1]));
      $ord_arr[2] = abs(intval($ord_arr[2]));
      $ord_arr[3] = abs(intval($ord_arr[3]));
      $order_id = $ord_arr[0].'-'.$ord_arr[1].'-'.$ord_arr[2].'-'.$ord_arr[3];

      if(count($ord_arr) == 4 AND $ord_arr[0] == core::$set['market_prefix'] AND user::exists_id($ord_arr[1]) AND in_array($ord_arr[2], $tar_array))
      {
        $req = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.$ord_arr[1].'"');
        $data = $req->fetch_assoc();

        if($data['rights'] == 0)
        {
          core::$db->query('UPDATE `ds_users` SET `rights` = "'.$db->res($tar_rights[$ord_arr[2]]).'", `ordercode` = "'.core::$db->res($order_id).'", `desttime` = "'.(time() + ($tar_time[$ord_arr[2]]*31*24*3600)).'" WHERE `id` = "'.$ord_arr[1].'";');

          core::$db->query('INSERT INTO `ds_paid` SET
            `tarid` = "'.$db->res($ord_arr[2]).'",
            `userid` = "'.$ord_arr[1].'",
            `username` = "'.core::$db->res($data['login']).'",
            `paidid` = "'.core::$db->res($order_id).'",
            `summ` = "'.core::$db->res($tar_summ[$ord_arr[2]]).'",
            `paytime` = "'.time().'",
            `comm` = "'.core::$db->res($tar_tariff[$ord_arr[2]]).'";');

          new mail_temp('./data/engine/');
          mail_temp::assign('home', core::$home);
          mail_temp::assign('orderid', $order_id);
          mail_temp::assign('summ', $tar_summ[$ord_arr[2]]);
          mail_temp::assign('userid', $ord_arr[1]);
          $mail_body = mail_temp::get('mail_buy_good');
          smtp::mail('analytic-spy@i-tt.ru', ' ', lang('mail_head_good').' '.$order_id.' ('.core::$set['site_name_main'].')', $mail_body);
          //smtp::mail('sales@i-tt.ru', ' ', lang('mail_head_good').' '.$order_id.' ('.core::$set['site_name_main'].')', $mail_body);


          new mail_temp('./data/engine/');
          mail_temp::assign('home', core::$home);
          mail_temp::assign('orderid', $order_id);
          mail_temp::assign('time', $tar_time[$ord_arr[2]]);
          mail_temp::assign('summ', $tar_summ[$ord_arr[2]]);
          mail_temp::assign('userid', $ord_arr[1]);
          $mail_body_user = mail_temp::get('mail_buy_user');
          smtp::mail($data['mail'], ' ', 'Подписка активирована! ('.core::$set['site_name_main'].')', $mail_body_user);

          $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM');
        }
        else
          $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM');
      }
      else
        $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM'); //Хотя нужно куоускъе

    }
    elseif(in_array($val['state'],$rej_array))
      $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'REJECT');
    else
      $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'ASK_AGAIN');

}

header('Content-Type: text/html; charset=UTF-8');
echo json_encode($answer_arr);
