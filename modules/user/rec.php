<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(core::$user_id) denied();
 
$key = GET('id');

if(mb_strlen($key) != 32) denied();

$res = core::$db->query('SELECT * FROM `ds_users_inactive` WHERE `key` = "'.core::$db->res($key).'"');

if(!$res->num_rows) {
    denied();
} else {
    $data = $res->fetch_assoc();

    $pay_date = time();
    $query = core::$db->query('SELECT * FROM `ds_tariffs` WHERE `id` = "7"');
    $tariff = $query->fetch_assoc();

    if ($tariff['typetime'] == 0) {
        $end_date = $pay_date + $tariff['longtime'] *24*3600;
    } elseif ($tariff['typetime'] == 1){
        $end_date = $pay_date + $tariff['longtime'] * 31*24*3600;
    }
    
  
    $userID = core::$db->insert('INSERT INTO `ds_users` SET
            `login`="'.core::$db->res($data['login']).'",
            `password`="'.$data['password'].'",
            `mail`="'.core::$db->res($data['mail']).'",
            `rights`="11",
            `time`="'.$data['time'].'",
            `subscribe` = "1",
            `desttime` = "'.$end_date.'",
            `ordercode` = "'.$pay_date.'",
            `ip`="'.core::$db->res(core::$ipl).'",
            `ua`="'.core::$db->res(core::$ua).'";');
    
    
    
    core::$db->query('INSERT INTO `ds_paid` SET
                            `tarid` = "'.$tariff['id'].'",
                            `userid` = "'.$userID.'",
                            `username` = "'.core::$db->res($data['login']).'",
                            `paidid` = "'.$pay_date.'",
                            `summ` = "'.$tariff['price'].'",
                            `paytime` = "'.$pay_date.'",
                            `comm` = "'.core::$db->res($tariff['name']).'";');

    core::$db->query('DELETE FROM `ds_users_inactive` WHERE `id` = "'.$data['id'].'";');
    
    $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment"');
    $mailTemplate = $query->fetch_assoc();
        
    $body = array(
        'name'      => $data['login'],
        'taiff'     => $tariff['name'],
        'number'    => $pay_date,
        'date'      => date('d.m.Y', $pay_date),
        'enddate'   => date('d.m.Y', $end_date)
    );
        
    $mail = mailer::factory();
    $mail->setSubject($mailTemplate['subject']);
    $mail->setBody($mailTemplate['template'], $body);
    $mail->addAddress($data['mail']);
    $mail->send();
  
    func::notify(lang('activate'), lang('act_succ'), core::$home, lang('home'));  
}