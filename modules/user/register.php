<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
{
 if(core::$set['reg'])
 {   
  if(GET('act'))
  {
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
    if(!$pass)
      $error[] = lang('empty_pass');
    elseif(mb_strlen($pass) < 4 or mb_strlen($pass) > 15)
    {
      $error[] = lang('pass_wrong_len');
      $pass = '';  
    }
      
    $pass_rep = POST('pass_rep');
    if($pass != $pass_rep)
    {
      $error[] = lang('miss_pass');
      $pass = '';
      $pass_rep = ''; 
    } 
    
    $mail = POST('mail');
    if(!$mail)
      $error[] = lang('miss_mail');             //Регулярка отсюда: http://fightingforalostcause.net/misc/2006/compare-email-regex.php
    elseif(!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$mail))
      $error[] = lang('wrong_mail');
    elseif(func::denied_mail($mail))
      $error[] = lang('error_mail');
    elseif(user::mail_exists($mail))
      $error[] = lang('mail_ex');
      
    //$sex = POST('sex');
    //if(!$sex)
    //  $error[] = lang('miss_sex');
    //elseif($sex != 'm' and $sex != 'w')
    //  $error[] = lang('err_sex');
  
    if(!func::capcha())
      $error[] = lang('miss_capcha');
  
    if(!$error)
    {
      $pass_mail = md5(func::passgen(rand(15,30)));
      new mail_temp('./data/engine/'); 
      
      //Добавляем в шаблон необходимые фразы
      mail_temp::assign('mail_th_reg', lang('mail_th_reg'));
      mail_temp::assign('mail_link_reg', lang('mail_link_reg'));
      mail_temp::assign('mail_warn_del', lang('mail_warn_del'));
      mail_temp::assign('mail_data_log', lang('mail_data_log'));
      mail_temp::assign('mail_log', lang('mail_log'));
      mail_temp::assign('mail_pass', lang('mail_pass'));
      mail_temp::assign('mail_th_und', lang('mail_th_und'));
      mail_temp::assign('mail_ign', lang('mail_ign'));
      
      //Добавляем в шаблон данные
      mail_temp::assign('login', $nick);
      mail_temp::assign('pass', $pass);
      mail_temp::assign('home', core::$home);
      mail_temp::assign('link', core::$home.'/user/rec?id='.$pass_mail);
      $mail_body = mail_temp::get('mail_temp');
      
      $subject = '=?utf-8?B?'.base64_encode(lang('mail_head')).'?=';
      $adds = 'Content-Type: text/plain; charset="utf-8"';
      $adds .= 'From: <'.''.'>';
      $adds .= 'Subject: '.$subject;
      $adds .= 'Content-Type: text/plain; charset="utf-8"';
      
      mail($mail, $subject, $mail_body, $adds);
      
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
      
      func::notify(lang('register'), lang('reg_succ'), core::$home);
    }
    else
    {
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
  engine_head(lang('register'));
  temp::HTMassign('capcha',func::img_capcha());
  temp::display('user.register');
  engine_fin();
 }
 else
  func::notify(lang('register'), lang('reg_den'), core::$home, lang('reg_back'));
}
else
  denied();