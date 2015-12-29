<?php

defined('DS_ENGINE') or die('access denied');

//запуск рассылки
if(isset($_GET['action']) && $_GET['action'] === 'start' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    core::$db->query('UPDATE `mail_mailing` SET `status` = "1" WHERE `id` = "'.$id.'"');
    func::notify('Рассылка', 'Рассылка запущена', core::$home . '/control/mail/mailinglist');
}

//остановка рассылки
if(isset($_GET['action']) && $_GET['action'] === 'stop' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    core::$db->query('UPDATE `mail_mailing` SET `status` = "2" WHERE `id` = "'.$id.'"');
    func::notify('Рассылка', 'Рассылка остановлена', core::$home . '/control/mail/mailinglist');
}

//удаление рассылки
if(isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    core::$db->query('DELETE FROM `mail_mailing` WHERE `id` = "'.$id.'"');
    
    $query = core::$db->query('SELECT * FROM `mail_mailing_files` WHERE `mail_id` = "'.$id.'"');
    
    $files = $query->fetch_all();

    foreach ($files as $file) {
        core::$db->query('DELETE FROM `mail_mailing_files` WHERE `mail_id` = "'.$id.'"');
        unlink('data/mailing/' . $id . '/' . $file['name']);
    }
    rmdir('data/mailing/' . $id);

    func::notify('Рассылка', 'Рассылка успешно удалена', core::$home . '/control/mail/mailinglist');
}

if (!empty($_POST)) {
    
    $subject = $_POST['subject'];
    $text = $_POST['text'];
    $groups = json_encode($_POST['groups']);
    
    $mailId = core::$db->insert('INSERT INTO `mail_mailing` (`groups`, `subject`, `text`, `created`) 
                                    VALUES ("'.core::$db->res($groups).'", "'.core::$db->res($subject).'", "'.core::$db->res($text).'", "'.time().'")');

    $dir = 'data/mailing/' . $mailId;
    mkdir($dir, 0777);
    
    if (!empty($_POST['images'])) {
        foreach ($_POST['images'] as $image) {
            core::$db->insert('INSERT INTO `mail_mailing_files` (`mail_id`, `name`, `type`) 
                                VALUES ("'.$mailId.'", "'.$image.'", "image")');
            
            $tmpFile = 'data/tmp/mailing/' . $image;
            $file = $dir . '/' . $image;
            
            copy($tmpFile, $file);
            unlink($tmpFile);
        }
    }
    
    if (!empty($_POST['files'])) {
        foreach ($_POST['files'] as $file) {
            core::$db->insert('INSERT INTO `mail_mailing_files` (`mail_id`, `name`, `type`) 
                                VALUES ("'.$mailId.'", "'.$file.'", "attachments")');
            
            $tmpFile = 'data/tmp/mailing/' . $file;
            $file = $dir . '/' . $file;
            
            copy($tmpFile, $file);
            unlink($tmpFile);
        }
    }

    func::notify('Рассылка', 'Рассылка успешно создана', core::$home . '/control/mail/mailinglist');
}

// редактирование рассылки
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    engine_head('Редактирование рассылки');
    
    $id = intval($_GET['id']);
    
    $query = core::$db->query('SELECT * FROM `mail_mailing` WHERE `id` = "'.$id.'"');
    $mail = $query->fetch_assoc();
    $mail['groups'] = json_decode($mail['groups'], true);
    
    $query = core::$db->query('SELECT * FROM `mail_mailing_files` WHERE type="image" AND `mail_id` = "'.$id.'"');
    $images = $query->fetch_all();
    
    $query = core::$db->query('SELECT * FROM `mail_mailing_files` WHERE type="attachments" AND `mail_id` = "'.$id.'"');
    $attachments = $query->fetch_all();
    
    temp::HTMassign('mail', $mail);
    temp::HTMassign('images', $images);
    temp::HTMassign('attachments', $attachments);
}

engine_head('Редактирование рассылки');
temp::display('control/mail/create');
engine_fin();