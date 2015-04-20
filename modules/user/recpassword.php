<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
{
  $error = array();
    
  if(POST('submit'))
  { 
 
    if(!func::capcha())
      $error[] = lang('miss_capcha');
    else
    {
      $mail = POST('mail');
      if(!$mail)
        $error[] = lang('miss_mail');             //Регулярка отсюда: http://fightingforalostcause.net/misc/2006/compare-email-regex.php
      elseif(!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$mail))
         $error[] = lang('wrong_mail');
      elseif(!user::mail_exists($mail))
        $error[] = lang('mail_ex');  
    }
    
    if(!$error)
    {
      $pass_mail = md5(func::passgen(rand(15,30)));  
      new mail_temp('./data/engine/');
      
      //Добавляем в шаблон необходимые фразы
      mail_temp::assign('mail_th_und', lang('mail_th_und'));
      mail_temp::assign('mail_ign', lang('mail_ign'));
      mail_temp::assign('init_rec', lang('init_rec'));
      mail_temp::assign('to_continue', lang('to_continue'));
      
      //Добавляем в шаблон данные
      mail_temp::assign('home', core::$home);
      mail_temp::assign('link', core::$home.'/user/newpass?id='.$pass_mail);
      $mail_body = mail_temp::get('recpassword');
       
      
      $rec = core::$db->query('SELECT * FROM `ds_users` WHERE `mail` = "'.core::$db->res($mail).'" LIMIT 1;');
      $data = $rec->fetch_assoc(); 
      
      core::$db->query('INSERT INTO `ds_recpassword` SET `userid`="'.$data['id'].'", `num`="'.core::$db->res($pass_mail).'"; ');

      mail::send($mail, lang('mail_head').' ('.lang('site_head').')', $mail_body);

      func::notify(lang('rec_pass'), lang('reg_succ'), core::$home);  
    }  
  }
    
  engine_head(lang('rec_pass'));
  temp::HTMassign('error', $error);
  temp::HTMassign('capcha',func::img_capcha());
  temp::display('user.recpassword');
  engine_fin();  
}
else
  denied();
