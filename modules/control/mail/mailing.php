<?php

defined('DS_ENGINE') or die('access denied');


if(isset($_GET['action']) && $_GET['action'] === 'test' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $mailingQuery = core::$db->query('SELECT * FROM `mail_mailing` WHERE `id` = "'.$id.'" LIMIT 1');
    $mail = $mailingQuery->fetch_assoc();
       
    $query = core::$db->query('SELECT * FROM `ds_users` WHERE `rights` = 100 ORDER BY `id` DESC LIMIT 10');
    while($user = $query->fetch_assoc()) {
        $body = [
            'host'  => core::$home,
            'text'  => $mail['text_compiled'],
            'hash'  => md5($user['id'])
        ];
    
        $mailer = mailer::factory();
        $mailer->setSubject($mail['subject']);
        $mailer->setBody('mailing', $body);
        $mailer->addAddress($user['mail']);
        $mailer->send();
    }
    exit;
}

//запуск рассылки
if(isset($_GET['action']) && $_GET['action'] === 'start' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $query = core::$db->query('SELECT * FROM `mail_mailing` WHERE `start_time` = "0" AND `id` = "'.$id.'"');
    if($query->num_rows > 0 ) {
        core::$db->query('UPDATE `mail_mailing` SET `status` = "1", `start_time` = "'.time().'" WHERE `id` = "'.$id.'" AND `start_time` = "0"');
    } else {
        core::$db->query('UPDATE `mail_mailing` SET `status` = "1" WHERE `id` = "'.$id.'"');
    }
    
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

// функция обработки текста
function prepare_text($text, $images, $dir)
{
    $replace_files = function($match) use ($images, $dir) {
        if(in_array($match[2], $images)) {
            $link = core::$home . '/' . $dir . '/' .$match[2];
            $image = '<a target="_blank" href="'.$link.'"><img style="width:100%; height:auto;" alt="'.$match[3].'" src="'.$link.'"/></a>';
            return $image;
        }
        return $match[2];
    };
    
    $text = preg_replace_callback('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', $replace_files, $text);
    return $text;
}


if (!empty($_POST)) {
    
    $subject = $_POST['subject'];
    $text = $_POST['text_source'];
    $groups = $_POST['groups'];
    $mailId = !empty($_POST['id']) ? intval($_POST['id']) : 0;
    
    // обновляем рассылку
    if(!empty($mailId)) {
        
        $dir = 'data/mailing/' . $mailId;
        
        $text_compiled = text::out($text);
        $text_compiled = prepare_text($text_compiled, $_POST['images'], $dir);
        
        
        core::$db->query('UPDATE `mail_mailing` SET 
                            `text_source` = "'.core::$db->res($text).'",
                            `text_compiled` = "'.core::$db->res($text_compiled).'",
                            `subject` = "'.core::$db->res($subject).'",
                            `groups` = "'.core::$db->res($groups).'"
                            WHERE id = "'.$mailId.'"
        ');
        $message = 'Рассылка успешно обновлена';
        
    } else {
        
        $mailId = core::$db->insert('INSERT INTO `mail_mailing` (`groups`, `subject`, `text_source`, `created`) 
                                   VALUES ("'.core::$db->res($groups).'", 
                                            "'.core::$db->res($subject).'", 
                                            "'.core::$db->res($text).'", 
                                            "'.time().'")');
        
        $dir = 'data/mailing/' . $mailId;
        mkdir($dir, 0777);
        
        $text_compiled = prepare_text($text, $_POST['images'], $dir);
        $text_compiled = text::out($text_compiled);
        
        core::$db->query('UPDATE `mail_mailing` SET `text_compiled` = "'.core::$db->res($text_compiled).'"');
        
        $message = 'Рассылка успешно создана';
        
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
    }

    func::notify('Рассылка', $message, core::$home . '/control/mail/mailinglist');
}

// редактирование рассылки
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    engine_head('Редактирование рассылки');
    
    $id = intval($_GET['id']);
    
    $query = core::$db->query('SELECT * FROM `mail_mailing` WHERE `id` = "'.$id.'"');
    $mail = $query->fetch_assoc();
    $mail['groups'] = $mail['groups'];
    
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
