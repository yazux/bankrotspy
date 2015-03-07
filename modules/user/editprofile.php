<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id)
{
  $id = intval(abs(GET('id')));
  if(!$id)
    denied();
  
  $res = core::$db->query('SELECT * FROM  `ds_users` WHERE `id` = "'.core::$db->res($id).'" LIMIT 1');
  if($res->num_rows)
  {
    $data = $res->fetch_array();
    $user_info = unserialize($data['info']);
    $user_set = unserialize($data['settings']);
      
    if(CAN('edit_user', $data['rights']) or core::$user_id == $id)
    {
      $error = array();  
        
      $act = GET('act');
      if($act)
      {
        if(CAN('edit_user', $data['rights']))
        {
          $nick = POST('nick');
          if($nick != $data['login'])
          {
            if(!$nick)
              $error[] = lang('empty_nick');
            elseif(!user::len_nick($nick))
              $error[] = lang('nick_wrong_len');
            elseif(!user::valid_nick($nick))
              $error[] = lang('nick_wrong');
            elseif(user::exists($nick))
              $error[] = lang('nick_ex');
        
            $change[] = ' `login` = "'.core::$db->res($nick).'" ';
          }
        
          $sex = POST('sex');
          if($sex != $data['sex'])
          {
            if(!$sex)
              $error[] = lang('miss_sex');
            elseif($sex != 'm' and $sex != 'w')
              $error[] = lang('err_sex');
                
            $change[] = ' `sex` = "'.core::$db->res($sex).'" ';  
          }
        
          
          $rights = intval(abs(POST('rights')));
          if($rights != $data['rights'])
          {
            $eng_right = user::get_rights();
            if(!isset($eng_right[$rights]))
              $error[] = lang('rights_error');
              
            $change[] = ' `rights` = "'.core::$db->res($rights).'" ';
          }
        }
        
        $getdata = array();
        $getdata['name'] = POST('name');
        $getdata['from'] = POST('from');
        $getdata['phone'] = POST('phone');
        $getdata['site'] = POST('site');
        $getdata['icq'] = POST('icq');
        $getdata['interests'] = POST('interests');
        $getdata['about'] = array(POST('about'),text::presave(POST('about'), 1));
        
        $born_day = intval(abs(POST('born_day')));
        $born_month = intval(abs(POST('born_month')));
        $born_year = intval(abs(POST('born_year')));
        
        if($born_day)
        {
          if($born_day > 31)
            $error[] = lang('error_day');
        }
        else
         $born_day = ''; 
        
        if($born_month)
        {
          if($born_month > 12)
            $error[] = lang('error_month');
        }
        else
          $born_month = '';
        
        if($born_year)
        {  
          if($born_year > 2012 or $born_year < 1900)
            $error[] = lang('error_year');
        }
        else
          $born_year = '';
        
        if(!$born_day and  !$born_month and !$born_year)
          $getdata['age'] = '';
        elseif($born_day and $born_month and $born_year)
          $getdata['age'] = $born_day.'.'.$born_month.'.'.$born_year;
        else
          $error[] = lang('error_date'); 
        
        if($getdata != $user_info)
        {     
          $change[] = ' `info` = "'.core::$db->res(serialize($getdata)).'" ';
        }
          
        if(!$error and isset($change))
        {
          core::$db->query('UPDATE `ds_users` SET '.implode(',', $change).'  WHERE `id` = "'.core::$db->res($id).'" '); 
          func::notify(core::$user_id == $id ? lang('myprofile') : lang('profile').' '.$data['login'], lang('ch_succ'), core::$home.'/user/profile?id='.$id);  
        }   
        else
          $error[] = lang('no_changes');
      }
      
      engine_head(core::$user_id == $id ? lang('myprofile') : lang('profile').' '.$data['login']);
      temp::HTMassign('error',$error);
      temp::HTMassign('rights',user::get_rights());
      temp::assign('user_rights',$data['rights']);
      temp::assign('name_prof', core::$user_id == $id ? lang('myprofile') : lang('profile').' '.$data['login']);
      temp::assign('user_prof',$id);
      if(CAN('edit_user', $data['rights']))
        temp::assign('edit_moder',1);  
      temp::assign('nick',$data['login']);
      temp::assign('sex', $data['sex']);
      
      if(isset($user_info['age']) AND $user_info['age'])
      {
        $show_age = explode('.',$user_info['age']);
        temp::assign('born_day', $show_age[0]);
        temp::assign('born_month', $show_age[1]);
        temp::assign('born_year', $show_age[2]);
      }
                               
      foreach($user_info as $key => $value)
      {
        if(!$value)
          $value = '';
        if($key=='about')
        {  
          temp::assign($key,$value[0]);  
        }
        else
          temp::assign($key,$value);  
      }
      
      temp::display('user.editprofile');
      engine_fin();
    }
    else
     denied();   
  }
  else
    func::notify(lang('profile'), lang('us_not_ex'), core::$home, lang('home_back'));  
}
else
  denied();