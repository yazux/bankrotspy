<?php
defined('DS_ENGINE') or die('web_demon laughs');

if (!core::$user_id) {
    if (core::$set['reg']) {
        
        if (GET('act')) {
            $error = array();  
            $nick = POST('nick');
            
            if(!$nick)
                $error[] = lang('empty_nick');
            elseif(!user::len_nick($nick))
                $error[] = lang('nick_wrong_len');
            elseif(!user::valid_nick($nick))
                $error[] = lang('nick_wrong');
            elseif(user::exists($nick))
                $error[] = lang('nick_ex');
      
            $pass = POST('pass');
            
            if(!$pass){
                $error[] = lang('empty_pass');
            } elseif (mb_strlen($pass) < 4 or mb_strlen($pass) > 15) {
                $error[] = lang('pass_wrong_len');
                $pass = '';  
            }
      
            $pass_rep = POST('pass_rep');
            
            if ($pass != $pass_rep) {
                $error[] = lang('miss_pass');
                $pass = '';
                $pass_rep = ''; 
            }
    
            $mail = POST('mail');
            if(!$mail)
                $error[] = lang('miss_mail');
            elseif(!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$mail))
                $error[] = lang('wrong_mail');
            elseif(func::denied_mail($mail))
                $error[] = lang('error_mail');
            elseif(user::mail_exists($mail))
                $error[] = lang('mail_ex');
  
            if(!func::capcha())
                $error[] = lang('miss_capcha');
  
            
            if (!$error) {
                $pass_mail = md5(func::passgen(rand(15,30)));
                
                core::$db->query('INSERT INTO `ds_users_inactive` SET
                                            `login`="'.core::$db->res($nick).'",
                                            `password`="'.md5(md5($pass)).'",
                                            `key`="'.core::$db->res($pass_mail).'",
                                            `mail`="'.core::$db->res($mail).'",
                                            `time`="'.time().'",
                                            `ip`="'.core::$db->res(core::$ipl).'",
                                            `ua`="'.core::$db->res(core::$ua).'",
                                            `lang`="'.core::$lang.'";
                ');
                
                $query = core::$db->query('SELECT * FROM `mail_templates` WHERE name = "register"');
                $mailTemplate = $query->fetch_assoc();
        
                $body = array(
                    'site'      => core::$home,
                    'link'      => core::$home.'/user/rec?id='.$pass_mail,
                    
                );
                $mailer = mailer::factory();
                $mailer->setSubject($mailTemplate['subject']);
                $mailer->setBody($mailTemplate['template'], $body);
                $mailer->addAddress($mail);
                $mailer->send();
                
                func::notify(lang('register'), lang('reg_succ'), core::$home);
            } else {
                engine_head(lang('register'));  
                temp::HTMassign('error',$error);
                temp::assign('nick',$nick);
                temp::assign('pass',$pass);
                temp::assign('pass_rep',$pass_rep);
                temp::assign('mail',$mail);
                //temp::assign('sex',$sex);
                temp::HTMassign('capcha',func::img_capcha());
                temp::display('user.register');
                engine_fin();
            }
        }

        $res = core::$db->query('SELECT * FROM  `ds_reg_page` WHERE `id` = "1";');
        $data = $res->fetch_assoc();
        text::add_cache($data['cache']);
        $text =  text::out($data['text'], 0, $data['id']);

        engine_head(lang('register'));
        temp::HTMassign('capcha',func::img_capcha());
        temp::HTMassign('text', $text);
        temp::display('user.register');
        engine_fin();
    } else {
        func::notify(lang('register'), lang('reg_den'), core::$home, lang('reg_back'));
    }
    
} else {
    header('Location:'.core::$home);
    exit();
}