<?php

defined('DS_ENGINE') or die('access denied');

$query = core::$db->query('SELECT * FROM `ds_users` WHERE `subscribe` = "0"');
$users = $query->fetch_assoc();


engine_head('Отписавшиеся');


temp::HTMassign('users', $users);
temp::display('control/mail/unsubscribing');
engine_fin();