<?php
defined('DS_ENGINE') or die('web_demon laughs');

require_once $_SERVER['DOCUMENT_ROOT'] . '/dscore/libs/phpmailer/PHPMailerAutoload.php';

$error = array();  

$lotid = !empty(GET('lotid')) ? GET('lotid') : '';
$name   = !empty(POST('name')) ? POST('name') : '';
$email  = !empty(POST('email')) ? POST('email') : '';
$skype  = !empty(POST('skype')) ? POST('skype') : '';
$text   = !empty(POST('text')) ? POST('text') : '';

if (GET('act')) {
    
    if(!$name) {
        $error[] = lang('empty_name');
    } 
    if(!$email) {
        $error[] = lang('empty_email');
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = lang('incorrect_email');
    }
    
    if(!$text) {
        $error[] = lang('empty_text');
    } elseif(mb_strlen($text) < 4) {
        $error[] = lang('length_text');
    }
    
    if(!func::capcha()) {
      $error[] = lang('miss_capcha');
    }
    
    if(!$skype) {
        $skype = 'не указан';
    }
  
    if(!$error) {
        
        $insertID = core::$db->insert('INSERT INTO `ds_assistance` SET
            `name`="'.core::$db->res($name).'",
            `email`="'.core::$db->res($email).'",
            `skype`="'.core::$db->res($skype).'",
            `text`="'.core::$db->res($text).'",
            `created`="'.time().'"
        ');
        
        $href= '';
        
        if(!empty($lotid)) {
            $lothref = core::$home . '/card/' . $lotid;
            $href = 'Лот: <a href="' . $lothref . '">' . $lothref . '</a><br/>';
        }
                
        $mail = mailer::factory('./data/engine/');
        
        $body = array(
            'name'  => $name,
            'email' => $email,
            'skype' => $skype,
            'href'  => $href,
            'text'  => $text
        );
        
        $subject = array(
            'id' => $insertID
        );
        
        $mail->setSubject(lang('success_head'), $subject);
        $mail->setBody('assistance', $body);
        $mail->addAddress('sales@i-tt.ru');
        $mail->addAddress('ak@i-tt.ru');
        $mail->send();
       
        $data = array(
            'id' => $insertID,
            'name' => $name
        );
        
        $mail = mailer::factory('./data/engine/');
        
        if(!core::$user_id) {
            $mail->setSubject(lang('user_head'));
            $mail->setBody('assistance_registration', $data);
            $mail->addAddress($email);
            $mail->send();
        } else {
            $mail->setSubject(lang('user_head'));
            $mail->setBody('assistance_noregistration', $data);
            $mail->addAddress($email);
            $mail->send();
        }
        
        func::notify(lang('title'), lang('success'), core::$home);
    } 
}

engine_head(lang('title'));
temp::HTMassign('error',$error);


temp::assign('name',$name);
temp::assign('email',$email);
temp::assign('skype',$skype);
temp::assign('text',$text);
temp::assign('lotid',$lotid);

temp::HTMassign('capcha',func::img_capcha());
temp::display('assistance.index');
engine_fin();