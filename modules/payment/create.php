<?php

defined('DS_ENGINE') or die('web_demon laughs');

// Если это не админ, выходим из скрипта
if (core::$rights < 100) denied();

$user_id = (int)$_POST['user_id'];
$tariff_id = (int)$_POST['tariff_id'];

// Получаем тариф по ID
$query = core::$db->query('SELECT * FROM `ds_tariffs` WHERE id = "'.$tariff_id.'" LIMIT 1');
$tariff = $query->fetch_assoc();

// Получаем пользователя
$query = core::$db->query('SELECT * FROM `ds_users` WHERE id = "'.$user_id.'" LIMIT 1');
$user = $query->fetch_assoc();

// Если пользователь имеет права и такой тариф существует
if ($user['rights'] == 0 && !empty($tariff)) {
    $pay_date = time();
    
    $order_code = 'bspy;' . $user_id . ';' . $tariff_id . ';' . $pay_date;
    
    //подписка 0 - дней 1 - месяцев
    if ($tariff['typetime'] == 0) {
        $end_date = $pay_date + $tariff['longtime'] *24*3600;
    } elseif ($tariff['typetime'] == 1){
        $end_date = $pay_date + $tariff['longtime'] * 31*24*3600;
    }

    core::$db->query('UPDATE `ds_users` SET
                                `rights` = "'.$tariff['rights'].'",
                                `desttime` = "'.$end_date.'",
                                `ordertimeid` = "'.$pay_date.'",
                                `ordercode` = "'.$order_code.'"
                            WHERE `id` = "'.$user_id.'";');
    
    core::$db->query('INSERT INTO `ds_paid` SET
                            `tarid` = "'.$tariff['id'].'",
                            `userid` = "'.$user_id.'",
                            `username` = "'.core::$db->res($user['login']).'",
                            `paidid` = "'.core::$db->res($order_code).'",
                            `summ` = "'.$tariff['price'].'",
                            `paytime` = "'.$pay_date.'",
                            `comm` = "'.core::$db->res($tariff['name']).'";');
    $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment"');
    $data = $query->fetch_assoc();
        
    $body = array(
        'name'      => $user['login'],
        'taiff'     => $tariff['name'],
        'orderid'    => $order_code,
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
} /*else {
    $responce = [
        'error' => 1,
        'message' => 'У пользователя есть подписка'
    ];
}*/