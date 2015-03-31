<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id)
{
  function text_age($year)
  {
    $year = abs($year);
    $t1 = $year % 10;
    $t2 = $year % 100;
    return ($t1 == 1 && $t2 != 11 ? lang('year_1') : ($t1 >= 2 && $t1 <= 4 && ($t2 < 10 || $t2 >= 20) ? lang('year_2') : lang('year_3')));
  }
  
  function zodiac($d, $m)
  {
    //Функция отсюда: http://old.snippets.pp.ru/article/110 
    $d=sprintf('%02d',$d);
    $m=sprintf('%02d',$m);
 
    if (($m=='03' AND $d>20) OR ($m=='04' AND $d<21)) return lang('zn_4');
    if (($m=='04' AND $d>20) OR ($m=='05' AND $d<22)) return lang('zn_5');
    if (($m=='05' AND $d>21) OR ($m=='06' AND $d<22)) return lang('zn_6');
    if (($m=='06' AND $d>21) OR ($m=='07' AND $d<23)) return lang('zn_7');
    if (($m=='07' AND $d>22) OR ($m=='08' AND $d<24)) return lang('zn_8');
    if (($m=='08' AND $d>23) OR ($m=='09' AND $d<24)) return lang('zn_9');
    if (($m=='09' AND $d>23) OR ($m=='10' AND $d<24)) return lang('zn_10');
    if (($m=='10' AND $d>23) OR ($m=='11' AND $d<23)) return lang('zn_11');
    if (($m=='11' AND $d>22) OR ($m=='12' AND $d<22)) return lang('zn_12');
    if (($m=='12' AND $d>21) OR ($m=='01' AND $d<19)) return lang('zn_1');
    if (($m=='01' AND $d>20) OR ($m=='02' AND $d<19)) return lang('zn_2');
    if (($m=='02' AND $d>18) OR ($m=='03' AND $d<21)) return lang('zn_3');
  } 
    
  $id = intval(abs(GET('id')));
  if(!$id) $id = core::$user_id;
  
  $res = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.core::$db->res($id).'" LIMIT 1');
  if($res->num_rows)
  {
    $data = $res->fetch_array();
    $user_info = unserialize($data['info']);
    $user_set = unserialize($data['settings']);
    
    // Пол
    if($data['sex']=='m')
      $sex = lang('man');
    elseif($data['sex']=='w')
      $sex = lang('woman');
    else
      $sex = lang('none');
    
    //Возраст и знак зодиака
    if($user_info['age'])
    {
      $age_arr = explode('.', $user_info['age']);
      $year_birth = $age_arr[2];
      $age = date('Y') - $age_arr[2];
      $age = $age.' '.text_age($age);
      if($age_arr[0] != 0 and $age_arr[1] != 0)
        $zodiac = zodiac($age_arr[0], $age_arr[1]);
    }
    else
      $age = '';
    
    
    // Права
    $eng_right = user::get_rights();
    
    //Аватарка
    $avatar = user::get_avatar($id, $data['avtime'], 1);
    
    engine_head(core::$user_id == $id ? lang('myprofile') : lang('anketa').' '.$data['login']);  
    foreach($user_info as $key => $value)
    {
      if($key == 'about')
      {
         text::add_cache($value[1], 1); 
         temp::HTMassign($key,text::out($value[0],$data['rights'])); 
      }  
      elseif($key == 'site')
        temp::HTMassign($key,text::out_url($value));
      else  
        temp::assign($key,$value);  
    }

    temp::assign('us_from','profile');
    temp::assign('name_prof', core::$user_id == $id ? lang('myprofile') : lang('anketa').' '.$data['login']);
    temp::assign('username',$data['login']);
    temp::assign('age',$age);
    temp::assign('rights',$eng_right[$data['rights']]);
    temp::assign('user_prof',$id);
    temp::assign('comm_plus', $data['comm_plus']);
    temp::assign('comm_minus', $data['comm_minus']);
    temp::assign('rep', ($data['comm_plus']-$data['comm_minus'])); 
    temp::assign('avatar', $avatar);
    temp::assign('online', user::is_online($data['lastvisit']));
    temp::assign('sex',$sex);
    if(isset($zodiac))
      temp::assign('zodiac',$zodiac);
    
    if(CAN('del_user', $data['rights']) AND $data['id'] != core::$user_id)
       temp::assign('can_del_user',1);
    if(CAN('edit_user', $data['rights']) or core::$user_id == $id)
      temp::assign('user_edit',1);
    if(CAN('ban_user', $data['rights']) and core::$user_id != $id)
      temp::assign('can_ban',1);
      
    if(isset($user_set['show_mail']) AND $user_set['show_mail'])
      temp::assign('mail',$data['mail']);
    temp::display('user.profile');
    engine_fin();    
  }
  else
  {
    func::notify(lang('profile'), lang('us_not_ex'), core::$home, lang('home_back'));  
  }
}
else
  denied(); 
