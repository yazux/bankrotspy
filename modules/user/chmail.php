<?php
defined('DS_ENGINE') or die('web_demon laughs');

if( core::$user_id ) {
    $error=array();
    if( POST('act') ) {
        $orig_pass = POST('orig_pass');  
        if(!$orig_pass)  
          $error[] = lang('miss_pass');
        elseif(mb_strlen($orig_pass) < 4 or mb_strlen($orig_pass) > 15)
          $error[] = lang('wrong_len_pass');
        elseif(md5(md5($orig_pass)) != core::$user_md_pass)
          $error[] = lang('pass_d_ex'); 

        $mail = POST('mail');
        if(!$mail)
          $error[] = lang('miss_mail');             //Регулярка отсюда: http://fightingforalostcause.net/misc/2006/compare-email-regex.php
        elseif(!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$mail))
          $error[] = lang('wrong_mail');
        elseif(func::denied_mail($mail))
          $error[] = lang('error_mail');
        elseif(user::mail_exists($mail))
          $error[] = lang('mail_ex');

        if( !$error ) {
            $pass_mail = md5(func::passgen(rand(15,30)));  
            new mail_temp('./data/engine/'); 

            mail_temp::assign('login', core::$user_name);
            mail_temp::assign('mail_link', core::$home.'/user/recmail?id='.$pass_mail);

            mail_temp::assign('mail_hello', lang('mail_hello'));
            mail_temp::assign('mail_chmail', lang('mail_chmail'));

            $mail_body = mail_temp::get('mail_chmail');  

            mail::send($mail, lang('mail_head'), $mail_body);

            core::$db->query('DELETE FROM `ds_chmail` WHERE `user_id` = "'.core::$db->res(core::$user_id).'";');
            core::$db->query('INSERT INTO `ds_chmail` SET
                `user_id`="'.core::$db->res(core::$user_id).'",
                `key`="'.core::$db->res($pass_mail).'",
                `oldmail`="'.core::$db->res(core::$user_mail).'",
                `mail`="'.core::$db->res($mail).'";');

            func::notify(lang('settings'), lang('mail_changed'), core::$home.'/user/cab', lang('continue'));
        }
    }  

    engine_head(lang('settings'));
    temp::HTMassign('error', $error);
    temp::display('user.chmail');
    engine_fin();
    
} else {
    header('Location:'.core::$home);
    exit();
}