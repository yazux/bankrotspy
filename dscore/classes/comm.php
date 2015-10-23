<?php
defined('DS_ENGINE') or die('web_demon laughs');


class comm
{
  public static $module;
  public static $action;
  public static $mid;
  public static $post_long;
  public static $total_mess;
  public static $page;
  public static $lastview = 0;
  private static $lastview_used = false;
  public static $sort;

  function __construct($module, $action, $mid)
  {
     self::$module = $module;
     self::$mid = $mid;
     self::$action = $action;
     self::$post_long = 2000;
     
     self::$total_mess = core::$db->query('SELECT COUNT(*) FROM `ds_comm` WHERE `module`="'.self::$module.'" AND `mid` = "'.self::$mid.'" ;')->count();
     if(nav::$kmess)
       self::$page = ceil(self::$total_mess/nav::$kmess);

     return true;
  }

  static function can_edit($userid, $rights)
  {  
    if(core::$user_id)
    {
      if((core::$user_id == $userid AND CAN('comm_self_edit', 0)) OR CAN('comm_edit', $rights))  
        return TRUE;
      else
        return FALSE;
    }
    else
      return FALSE;  
  }

  static function can_answer($userid, $rights)
  {
    if(core::$user_id)
    {
      if((core::$user_id != $userid AND CAN('create_comm', 0)))
        return TRUE;
      else
        return FALSE;
    }
    else
      return FALSE;
  }

  static function can_delete($userid, $rights)
  {
    if(core::$user_id)
    {
      if((core::$user_id == $userid AND CAN('comm_self_delete', 0)) OR CAN('comm_delete', $rights))  
        return TRUE;
      else
        return FALSE;
    }
    else
      return FALSE;  
  }

  static function total()
  {
     return self::$total_mess; 
  }

  static function view($start, $limit)
  {
    if(!self::$sort)
      $sort = 'ASC';
    else
      $sort = 'DESC';

    $res = core::$db->query('SELECT `ds_comm`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_comm` LEFT JOIN `ds_users` ON `ds_comm`.`userid` = `ds_users`.`id` WHERE `ds_comm`.`module`="'.self::$module.'" AND `ds_comm`.`mid` = "'.self::$mid.'" ORDER BY `ds_comm`.`time` '.$sort.' LIMIT '.$start.', '.$limit.';');
    
    $eng_right = user::get_rights();
    
    $i = 1;
    $arr = array();
    while($data = $res->fetch_assoc())
    {
      $out = array();
      $out['i'] = $i;
      $out['id'] = $data['id'];
      text::add_cache($data['cache']);
      $out['text'] = text::out($data['text'], $data['rights'], $data['id']);

      if(self::$lastview AND !self::$lastview_used)
      {
        if($data['time'] > self::$lastview)
        {
          $out['seen'] = 1;
          self::$lastview_used = true;
        }
      }

      $out['quote'] = addslashes(htmlentities('[c='.$data['userid'].']'.$data['text'].'[/c]', ENT_QUOTES, 'UTF-8')).'\n';
      
      if($data['rights'] > 0)
       $out['rights'] = $eng_right[$data['rights']];


      $out['to_quote'] = text::to_quote($data['text']);
      $out['rights_num'] = $data['rights']; 
      $out['sex'] = $data['sex'];
      $out['online'] = user::is_online($data['lastvisit']);
      
      $out['useredit'] = $data['useredit'];
      $out['editid'] = $data['editid'];
      $out['lastedit'] = ds_time($data['lastedit']);
      $out['numedit'] = $data['numedit'];
      
      $out['time'] = ds_time($data['time']);
      $out['id_user'] =  $data['userid'];
      $out['from_login'] =  $data['username'];

      $out['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
      
      $arr[] = $out;
      $i++;  
    }  
      
    return $arr;  
  }

  static function check_post($text = '')
  {
    $error = array();  
    if(!$text) 
      $error[] = lang('no_comm');
    
    if(mb_strlen($text) > self::$post_long)
      $error[] = lang('to_long_comm');
    
    if(func::antiflood())
      $error[] = lang('to_many_comms');
      
    if($error)
      return $error;
    else
      return FALSE;
  }

  static function add_post($text)
  {
    core::$db->query('INSERT INTO `ds_comm` SET
     `module` = "'.self::$module.'",
     `mid` = "'.self::$mid.'",
     `text`="'.core::$db->res($text).'",
     `cache`="'.core::$db->res(text::presave($text)).'",
     `username`="'.core::$user_name.'",
     `userid`="'.core::$user_id.'",
     `time`="'.time().'";
    ');
    
    self::$page = ceil((self::$total_mess+1)/nav::$kmess);
    
    $in_id = core::$db->insert_id;
    //Обновляем время последнего поста
    core::$db->query('UPDATE `ds_users` SET `lastpost` = "' . time() . '" WHERE `id` = "' . core::$user_id . '";'); 
      
    return $in_id;  
  }


  static function del_comms($id_stat, $module = '')
  {
    core::$db->query("DELETE FROM `ds_comm` WHERE `mid` = '".$id_stat."' AND `module` = '".$module."';");
    return TRUE;
  }
}