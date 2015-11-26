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

if ($decrypted_request) {
    $json_request = json_decode($decrypted_request, 1);
}

if (!isset($json_request)) {
    header('HTTP/1.1 401 Unauthorized');
    exit();
}

if (!isset($json_request['payments'])) {
    header('HTTP/1.1 503 Service Temporarily Unavailable');
    exit();
}

$rej_array = array('exp', 'err', 'rej', 'ref');
$answer_arr = array();
$answer_arr['payments'] = array();

$query = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');

//может быть пачка платежей
foreach($json_request['payments'] AS $key => $val) {
    
    if ($val['state'] === 'end') {
        
        $order_id = $val['orderId'];
        preg_match('#^(BSPY)-(\d{0,3})-(\d{0,1})-(\d{0,10})$#', $order_id, $order)
                
        $market_prefix = intval($order[1]);
        $user_id = intval($order[2]);
        $tariff_id = intval($order[3]);
        $order_time =intval($order[4]);
        
        $query = core::$db->query('SELECT * FROM `ds_tariffs` WHERE `id` = "'.$tariff_id.'"');
        $tariff = $query->fetch_assoc();
        
        if ($market_prefix == core::$set['market_prefix'] && 
                user::exists_id($user_id) &&
                    !empty($tariff)) {
                        
            
            $query = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.$user_id.'"');
            $user = $req->fetch_assoc();

            if($user['rights'] == 0) {
                
                $time = time();
                //подписка 0 - дней 1 - месяцев
                if ($tariff['typetime'] == 0) {
                    $date = $time + $tariff['longtime'] *24*3600;
                } elseif ($tariff['typetime'] == 0){
                    $date = $time + $tariff['longtime'] * 31*24*3600;
                }
                
                core::$db->query('UPDATE `ds_users` SET
                            `rights` = "'.$tariff['rights'].'",
                            `ordercode` = "'.core::$db->res($order_id).'",
                            `desttime` = "'.$date.'"
                        WHERE `id` = "'.$user_id.'";');
        
                core::$db->query('INSERT INTO `ds_paid` SET
                            `tarid` = "'.$tariff['id'].'",
                            `userid` = "'.$user_id.'",
                            `username` = "'.core::$db->res($user['login']).'",
                            `paidid` = "'.core::$db->res($order_id).'",
                            `summ` = "'.$tariff['price'].'",
                            `paytime` = "'.$time.'",
                            `comm` = "'.core::$db->res($tariff['name']).'";');

                $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment"');
                $data = $query->fetch_assoc();
        
                $body = array(
                    'name'      => $user['login'],
                    'taiff'     => $tariff['name'],
                    'number'    => $order_id,
                    'date'      => date('d.m.Y', $time),
                    'enddate'   => date('d.m.Y', $date)
                );
        
                $mail = mailer::factory();
                $mail->setSubject($data['subject']);
                $mail->setBody($data['template'], $body);
                $mail->addAddress($user['mail']);
                $mail->send();
        
                //системная почта
                $mail = mailer::factory();
                $mail->setSubject('Оплата подписки');
                $mail->setBody('Клиент: {$name}<br/>Тариф: {$taiff}<br/>Дата: {$date}', $body);
                $mail->addAddress('ak@i-tt.ru');
                $mail->addAddress('sales@i-tt.ru');
                $mail->send();
        
                $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM');
            } else {
                $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM');
            }
        } else {
            $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'CONFIRM'); //Хотя нужно куоускъе
        }
    } elseif(in_array($val['state'],$rej_array)) {
        $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'REJECT');
    } else {
        $answer_arr['payments'][$key] = array('orderId' => $val['orderId'], 'action' => 'ASK_AGAIN');
    }
}

header('Content-Type: text/html; charset=UTF-8');
echo json_encode($answer_arr);
