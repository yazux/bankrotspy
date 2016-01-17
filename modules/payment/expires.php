<?php

if (php_sapi_name() !== "cli") {
    exit;
}

$time = time();

$query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "payment_end"');
$mailTemplate = $query->fetch_assoc();

$query = core::$db->query('SELECT * FROM `ds_users` WHERE `rights` IN (10, 11) AND `desttime` > "0" AND `desttime` < "'.$time.'"');

while($row = $query->fetch_assoc()) {
    var_dump($row);
    core::$db->query('UPDATE `ds_users` SET
                        `rights` = "0",
                        `desttime` = "0",
                        `ordercode` = ""
                    WHERE `id` = "'.$row['id'].'";'
    );
    
    $body = array(
        'name'      => $row['login'],
    );
    
    $mailer = mailer::factory();
    $mailer->setSubject($mailTemplate['subject']);
    $mailer->setBody($mailTemplate['template'], $body);
    $mailer->addAddress($row['mail']);
    $mailer->send();
}
