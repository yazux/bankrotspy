<?php

defined('DS_ENGINE') or die('access denied');


$query = core::$db->query('SELECT m.*, COUNT(l.id) as totalsent 
                                    FROM `mail_mailing` AS m 
                                        LEFT JOIN `mail_mailing_log` AS l ON (m.id = l.mail_id) ORDER BY `id` DESC');

$mailing = [];

$statuses = [
    '0' => 'Новая',
    '1' => 'Запущена',
    '2' => 'Остановлена',
    '3' => 'Завершена'
];

$userQuery = core::$db->query('SELECT count(id) FROM `ds_users` WHERE `subscribe` = "1"');
$totalUsers = $userQuery->count();


$i = 0;
while ($mail = $query->fetch_assoc()) {
    $mailing[$i]['id'] = $mail['id'];
    $mailing[$i]['subject'] = $mail['subject'];
    $mailing[$i]['text'] = $mail['text'];
    $mailing[$i]['status'] = $statuses[$mail['status']];
    $mailing[$i]['status_act'] = $mail['status'];
    $mailing[$i]['created'] = !empty($mail['created']) ? date('d.m.Y H:i:s', $mail['created']) : '-';
    $mailing[$i]['start'] = !empty($mail['start_time']) ? date('d.m.Y H:i:s', $mail['start_time']) : '-';
    $mailing[$i]['end'] = !empty($mail['end_time']) ? date('d.m.Y H:i:s', $mail['end_time']) : '-';
    $mailing[$i]['totalsent'] = $mail['totalsent'];
    $mailing[$i]['totaluser'] = $totalUsers;
    
    $i++;
}

engine_head('Cписок рассылок');
temp::HTMassign('mailing', $mailing);
temp::display('control/mail/mailinglist');
engine_fin();