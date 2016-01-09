<?php
//echo base64_encode(file_get_contents('https://resize.yandex.net/mailservice?url=https%3A%2F%2Fmoney.yandex.ru%2Fi%2Fhtml-letters%2Fvk.png&proxy=yes&key=088935bfcb6bfad3a0706739263f4391'));exit;
$query = core::$db->query('SELECT * FROM `mail_mailing` WHERE status != "0" LIMIT 1');

$mail = $query->fetch_assoc();

$mailID = $mail['id'];


// селект кому еще не отправляли
$query = core::$db->query('SELECT * FROM `ds_users` WHERE (id) NOT IN 
                                        (SELECT user_id FROM `mail_mailing_log` WHERE mail_id = '.$mailID.') 
                                        ORDER BY id DESC LIMIT 10');


$mailer = mailer::factory();

$body = [
        'text'  => $mail['text_compiled'],
    ];
    
    $mailer->setSubject($mail['subject']);
    $mailer->setBody('mailing', $body);
    $mailer->addAddress('imbagroup@yandex.ru');
    $mailer->send();
exit;
                                        
while($user = $query->fetch_assoc()) {
    $user = core::$db->insert('INSERT INTO `mail_mailing_log` (mail_id, user_id) VALUES ("'.$mailID.'", "'.$row['id'].'")');
    
    
    $body = [
        'text'  => $mail['text_compiled'],
    ];
    
    $mailer->setSubject($mail['subject']);
    $mailer->setBody('mailing', $body);
    $mailer->addAddress($user['mail']);
    //$mailer->send();
}
//var_dump($mail);