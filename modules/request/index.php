<?php
defined('DS_ENGINE') or die('web_demon laughs');

require_once $_SERVER['DOCUMENT_ROOT'] . '/dscore/libs/phpmailer/PHPMailerAutoload.php';

$error = array();  

$lotid = GET('lotid');
if ( !$lotid ) {
    $lotid = POST('lotid');
}

$companies = array();
$resQuery = core::$db->query('SELECT * FROM `companies` WHERE 1');
while ( $company =  $resQuery->fetch_assoc() ) {
    $companies[$company['id']] = $company;
}
//var_dump($companies);

$companyId  = POST('company');
$name       = POST('name');
$email      = POST('email');
$city       = POST('city');
$inn        = POST('inn');
$phone      = POST('phone');

$act = GET('act');

if ( isset($act) && ($act == 'send') ) {
    
    if(!$name) {
        $error[] = lang('empty_name');
    }
    
    if(!$email) {
        $error[] = lang('empty_email');
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = lang('incorrect_email');
    }
    
    if(!$lotid) {
        $error[] = lang('empty_lot_id');
    }

    if(!$city) {
        $error[] = lang('empty_city');
    }
  
    if(!$phone) {
        $error[] = lang('empty_phone');
    }
  
    if(!$error) {
        
        $insertID = core::$db->insert('INSERT INTO `ds_request` SET
            `company_id`="'.(int)core::$db->res($companyId).'",
            `name`="'.core::$db->res($name).'",
            `email`="'.core::$db->res($email).'",
            `city`="'.core::$db->res($city).'",
            `phone`="'.core::$db->res($phone).'",
            `inn`="'.core::$db->res($inn).'",
            `lotid`="'.core::$db->res($lotid).'",
            `created`="'.time().'"
        ');
        
        $href= '';
        
        if(!empty($lotid)) {
            $lothref = core::$home . '/card/' . $lotid;
            $href = 'Лот: <a href="' . $lothref . '">' . $lothref . '</a><br/>';
        }
                
        $mail = mailer::factory('./data/engine/');
        
        $body = array(
            'company_id'  => $companyId,
            'name'  => $name,
            'phone'  => $phone,
            'email' => $email,
            'city' => $city,
            'href'  => $href,
            'inn'  => $inn,
            'lotid'  => $lotid
        );
        
        $subject = array(
            'id' => $insertID
        );
        
        $mail->setSubject(lang('success_head'), $subject);
        $mail->setBody('request', $body);
//        $mail->addAddress('sales@i-tt.ru');
        $mail->addAddress('rlopatkin@gmail.com');
//        $mail->addAddress($companies[$company]['email']);
        $mail->send();
       
        $data = array(
            'id' => $insertID,
            'name' => $name
        );
        
        $mail = mailer::factory('./data/engine/');
        
        if(!core::$user_id) {
            $mail->setSubject(lang('user_head'));
            $mail->setBody('request_registration', $data);
            $mail->addAddress($email);
            $mail->send();
        } else {
            $mail->setSubject(lang('user_head'));
            $mail->setBody('request_noregistration', $data);
            $mail->addAddress($email);
            $mail->send();
        }
        
        func::notify(lang('title'), lang('success'), core::$home);
    } 
}

$textQuery = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "11" LIMIT 1;');
$textData =  $textQuery->fetch_assoc();

engine_head($textData['name'], $textData['keywords'], $textData['description']);

temp::assign('title', $textData['name']);

temp::HTMassign('error',$error);
temp::HTMassign('page_text', text::out($textData['text'], 0));

temp::assign('name',$name);
temp::assign('email',$email);
temp::assign('phone',$phone);
temp::assign('city',$city);
temp::assign('inn',$inn);
temp::assign('lotid',$lotid);
temp::HTMassign('companies',$companies);

temp::display('request.index');
engine_fin();