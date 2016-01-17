<?php

if(!isset($_GET['data'])) { 
    exit;
}

//$request = json_decode('{"notification_type":"p2p-incoming","operation_id":"1011496739808126017","amount":9.95,"withdraw_amount":10,"currency":null,"datetime":"2016-01-10T13:39:29Z","sender":"410013399813469","codepro":"false","label":"bspy;392;1;1452433156","sha1_hash":"2dcd0fc23fbd663adfe84b2515005f3ab7afdaab"}', true);

$request = json_decode($_GET['data'], true);

$order_id = $request['operation_id']; // номер платежа


$order = explode(';', $request['label']);

$market = $order[0];
$user_id = intval($order[1]);
$tariff_id = intval($order[2]);
$pay_date = time();

$query = core::$db->query('SELECT * FROM `ds_tariffs` WHERE `id` = "'.$tariff_id.'"');
$tariff = $query->fetch_assoc();

if(floor($request['amount']) < intval($tariff['price'])) {
    die('incorrect sum: ' . $request['amount']);
}

if ($market === core::$set['market_prefix'] && user::exists_id($user_id) && !empty($tariff)) {
    
    $query = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.$user_id.'"');
    $user = $query->fetch_assoc();
    
    if ($user['rights'] == 0) {
        
        //подписка 0 - дней 1 - месяцев
        if ($tariff['typetime'] == 0) {
            $end_date = $pay_date + $tariff['longtime'] *24*3600;
        } elseif ($tariff['typetime'] == 1){
            $end_date = $pay_date + $tariff['longtime'] * 31*24*3600;
        }

        core::$db->query('UPDATE `ds_users` SET
                                `rights` = "'.$tariff['rights'].'",
                                `ordertimeid` = "'.$pay_date.'",
                                `desttime` = "'.$end_date.'",
                                `ordercode` = "'.$request['label'].'"
                            WHERE `id` = "'.$user_id.'";');
        
        core::$db->query('INSERT INTO `ds_paid` SET
                            `tarid` = "'.$tariff['id'].'",
                            `userid` = "'.$user_id.'",
                            `username` = "'.core::$db->res($user['login']).'",
                            `paidid` = "'.core::$db->res($order_id).'",
                            `summ` = "'.$tariff['price'].'",
                            `paytime` = "'.$pay_date.'",
                            `comm` = "'.core::$db->res($tariff['name']).'";');
        
        $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment"');
        $data = $query->fetch_assoc();
        
        $body = array(
            'name'      => $user['login'],
            'taiff'     => $tariff['name'],
            'orderid'    => $order_id,
            'date'      => date('d.m.Y', $pay_date),
            'enddate'   => date('d.m.Y', $end_date)
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
    }
}