<?php

defined('DS_ENGINE') or die('access denied');

if(isset($_GET['save'])) {
    foreach ($_POST['template'] as $id => $data) {
        core::$db->query('UPDATE `mail_templates` SET 
                            `subject` = "'.core::$db->res($data['subject']).'", 
                            `template` = "'.core::$db->res($data['template']).'"
                        WHERE `id` = "'.core::$db->res($id).'"');
    }
}

$query = core::$db->query('SELECT * FROM `mail_templates` ORDER BY sort ASC');

while($row = $query->fetch_assoc()) {
    $templates[] = $row;
}

engine_head('Управление шаблонами');
temp::HTMassign('templates', $templates);
temp::display('control/mail/templates');
engine_fin();