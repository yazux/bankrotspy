<?php

defined('DS_ENGINE') or die('web_demon laughs');

// Проверка на доступ к скрипту только админа
if( core::$rights != 100 ) {
    exit('Отказано в доступе.');
}

$userId = (int)$_GET['user_id'];
$tariffId = (int)$_GET['tariff_id'];

// Получаем пользователя
$query = core::$db->query('SELECT * FROM `ds_users` WHERE id = "'.$userId.'" LIMIT 1');
$user = $query->fetch_assoc();

// Если пользователь имеет права клиента или VIP-клиента и есть тарифы для выставления
if ( $user['rights'] < 12 ) {
    
    if ( $tariffId == -1 ) {
         // Убираем клиенту все подписки
        core::$db->query(
                'UPDATE `ds_users` 
                SET `desttime` = 0, `ordertimeid` = 0, `ordercode` = \'\'
                WHERE `id` = ' . $userId
            );
        
        exit('Пользователь отписан от всех подписок');
    } else {
        
        // Получаем тариф по ID
        $query = core::$db->query('SELECT * FROM `ds_tariffs` WHERE id = "'.$tariffId.'" LIMIT 1');
        $tariff = $query->fetch_assoc();
        
        if ( !empty($tariff) ) {
            // Подписываем пользователя на указаную подписку
            $resultMessage = "Выставлен новый тариф.";

            $pay_date = time();

            $order_code = 'bspy;' . $userId . ';' . $tariffId . ';' . $pay_date;

            //подписка 0 - дней 1 - месяцев
            if ($tariff['typetime'] == 0) {
                $end_date = $pay_date + $tariff['longtime'] *24*3600;
            } elseif ($tariff['typetime'] == 1){
                $end_date = $pay_date + $tariff['longtime'] * 31*24*3600;
            }

            // Выставялем клиенту нужные права и временной период доступа к материалам
            core::$db->query('UPDATE `ds_users` SET
                                        `rights` = "'.$tariff['rights'].'",
                                        `desttime` = "'.$end_date.'",
                                        `ordertimeid` = "'.$pay_date.'",
                                        `ordercode` = "'.$order_code.'"
                                    WHERE `id` = "'.$userId.'";');

            // Если указана сумма оплаты поверх тарифа
            $pay = (int)$_GET['pay'];
            if ( $pay > 0 ) {
                $sum = $pay;
            } else {
                $sum = $tariff['price'];
            }

            // Принимаем переменную
            $forFriend = (isset($_GET['forFriend']) && ($_GET['forFriend'] == 1) ? 1 : 0);

            // Если выдавался доступ как клиенту (платный), то создаем транзакцию 
            if ( $forFriend == 0 ) {
                core::$db->query('INSERT INTO `ds_paid` SET
                                        `tarid` = "'.$tariff['id'].'",
                                        `userid` = "'.$userId.'",
                                        `username` = "'.core::$db->res($user['login']).'",
                                        `paidid` = "'.core::$db->res($order_code).'",
                                        `summ` = "'.$sum.'",
                                        `paytime` = "'.$pay_date.'",
                                        `comm` = "'.core::$db->res($tariff['name']).'";');

                $resultMessage .= " Создана транзакция.";
            }

            // Проверим стоит ли галочка Уведомлять клиента
            $message = (isset($_GET['message']) && ($_GET['message'] == 1) ? 1 : 0);

            // Данные для письма
            $body = array(
                    'name'      => $user['login'],
                    'taiff'     => $tariff['name'],
                    'orderid'    => $order_code,
                    'date'      => date('d.m.Y', $pay_date),
                    'enddate'   => date('d.m.Y', $end_date)
                );

            // Уведомим киента о платеже и установке тарифа
            if ( $message ) {
                $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment"');
                $data = $query->fetch_assoc();

                $mail = mailer::factory();
                $mail->setSubject($data['subject']);
                $mail->setBody($data['template'], $body);
                $mail->addAddress($user['mail']);
                $mail->send();

                $resultMessage .= " Клиент уведомлен по почте.";
            }

            //системная почта
            $mail = mailer::factory();
            $mail->setSubject('Оплата подписки');
            $mail->setBody('Клиент: {$name}<br/>Тариф: {$taiff}<br/>Дата: {$date}', $body);
            $mail->addAddress('ak@i-tt.ru');
            $mail->addAddress('sales@i-tt.ru');
            $mail->send();

            exit( $resultMessage );
        } else {
            exit('Нет такого тарифа.');
        }
    }
} else {
    exit('Пользователь круче VIP-клиента.');
}