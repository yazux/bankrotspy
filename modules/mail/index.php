<?php

if (php_sapi_name() !== "cli") {
    exit;
}

if ($_SERVER['USER'] == 'bsd') {
    $host = 'http://bsd.bankrot-spy.ru';
} else {
    $host = 'http://bankrot-spy.ru';
}

$mailingQuery = core::$db->query('SELECT * FROM `mail_mailing` WHERE `status` NOT IN ("0","2","3") LIMIT 1');
$mail = $mailingQuery->fetch_assoc();

$mailID = $mail['id'];

// селект кому еще не отправляли
$query = core::$db->query('SELECT * FROM `ds_users` WHERE (`id`) NOT IN 
                                        (SELECT user_id FROM `mail_mailing_log` WHERE `mail_id` = "'.$mailID.'")
                                        AND `subscribe` = "1" ORDER BY `id` DESC LIMIT 10');

if (is_array($mail)) {                                        
    while($user = $query->fetch_assoc()) {
    
        $body = [
            'host'  => $host,
            'text'  => $mail['text_compiled'],
            'hash'  => createHash($user['id'], $salt)
        ];
    
        $mailer = mailer::factory();
        $mailer->setSubject($mail['subject']);
        $mailer->setBody('mailing', $body);
        $mailer->addAddress($user['mail']);
        if($mailer->send()) {
            core::$db->insert('INSERT INTO `mail_mailing_log` (mail_id, user_id, created) VALUES ("'.$mailID.'", "'.$user['id'].'", "'.time().'")');
        } else {
            $log = $mailer->debug();
            core::$db->query('INSERT INTO `mail_mailing_errors` (mail_id, error, user_id, created) 
                                        VALUES ("'.$mailID.'", "'.core::$db->res($log).'", "'.$user['id'].'", '.time().')');
        }
    }
}