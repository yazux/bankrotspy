<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$error = array();

if(!$id)
  denied();

$res = core::$db->query('SELECT * FROM `ds_news` WHERE `id` = "'.$id.'" LIMIT 1;');
if($res->num_rows) 
{ 
  $rs = $res->fetch_assoc();
  
  new nav(); //Постраничная навигация
  
  new comm(core::$module, core::$action, $id); //Класс для работы с камментами
  
  $total = comm::total();
  
  if(POST('submit'))
  {
    if(core::$user_id AND CAN('create_comm', 0))
    {   
      $post = POST('msg');
      $error = comm::check_post($post);
        
      if(!$error)
      {
        $warn[] = lang('comm_added');
      
        //Добавляем пост    
        comm::add_post($post);
        //Обьявляем всем что в статье есть новые комменты
        core::$db->query('UPDATE `ds_news` SET `comtime` = "' . time() . '" WHERE `id` = "' . $id . '";');
        //Пользователю добавившему пост автоматом защитываем просмотр
        $comr = core::$db->query('SELECT * FROM `ds_news_comm_rdm` WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";');
        if(!$comr->num_rows)
          core::$db->query('INSERT INTO `ds_news_comm_rdm` SET `modid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `rdmtime` = "'.time().'";');    
        else
          core::$db->query('UPDATE `ds_news_comm_rdm` SET `rdmtime` = "'.time().'" WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";');
      
        
        
        uscache::rem('mess_head', lang('mess_head'));
        uscache::rem('mess_body', lang('mess_body'));
         
        header('Location: ' . core::$home.'/news/view?id='.$id.'&page='.comm::$page.'#comms');
        exit();
      }
      else
      {
        uscache::rem('mess_head', lang('lng_err'));
        uscache::rem('mess_body', implode('<br/>', $error));
        
        header('Location: ' . core::$home.'/news/view?id='.$id.'&page='.comm::$page.'#comms');
        exit();  
      }
    }
    else
      denied();
  }
  
  //отмечаем новость как прочитанную + комментрарии
  if(core::$user_id)
  {
     $nowtime = time(); 
     // Проверка
     $us_query = '';
     if($rs['time'] > ($nowtime - (3 * 24 * 3600)))
       $us_query .= 'SELECT * FROM `ds_news_rdm` WHERE `artid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
     if($rs['comtime'] > ($nowtime - (3 * 24 * 3600)))
       $us_query .= 'SELECT * FROM `ds_news_comm_rdm` WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
     
     if($us_query)
     {
       $fin_sql = '';
       core::$db->multi_query($us_query);
       if($rs['time'] > ($nowtime - (3 * 24 * 3600)))
       {
         $creq = core::$db->store_result();
         if(!$creq->num_rows)
           $fin_sql .= 'INSERT INTO `ds_news_rdm` SET `artid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `time` = "'.time().'";';    
       }
       
       if(($rs['time'] > ($nowtime - (3 * 24 * 3600))) AND ($rs['comtime'] > ($nowtime - (3 * 24 * 3600))))
         core::$db->next_result();
       
       if($rs['comtime'] > ($nowtime - (3 * 24 * 3600)))
       {
         $creq = core::$db->store_result();
         if(!$creq->num_rows)
           $fin_sql .= 'INSERT INTO `ds_news_comm_rdm` SET `modid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `rdmtime` = "'.time().'";';  
         else
         {
           $cdata = $creq->fetch_array();
           if($rs['comtime'] > $cdata['rdmtime']) 
             $fin_sql .= 'UPDATE `ds_news_comm_rdm` SET `rdmtime` = "'.time().'" WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
         }
       }
       
       //Если все таки нужно отметить, то делаем запрос
       if($fin_sql)
       {
          core::$db->multi_query($fin_sql);
          core::$db->multi_free();
       }
     } 
  }
  
  // Выводим комментарии
  $comms = comm::view(nav::$start, nav::$kmess);
  
   engine_head(lang('news'), $rs['keywords'], $rs['description']);
  temp::HTMassign('error', $error);
  if(CAN('news_create', 0))
      temp::assign('can_cr_news', 1);
  if(CAN('news_edit', 0))    
      temp::assign('can_ed_news', 1);
  if(CAN('news_delete', 0))    
      temp::assign('can_del_news', 1);
  temp::assign('id', $id);
  temp::HTMassign('com',$comms);
  temp::assign('total',$total);
  if(core::$user_id and CAN('create_comm', 0))
      temp::assign('its_user',core::$user_id);
  temp::assign('newname', $rs['name']);
  
  $text = text::out($rs['text'], 0);
  $text = fload::replace_files($text, $id, core::$module);
  
  temp::HTMassign('text', $text);
  temp::assign('time', ds_time($rs['time']));
  temp::assign('login', $rs['login']);
  temp::assign('user_st_id', $rs['user_id']);
  temp::HTMassign('navigation', nav::display($total, core::$home.'/news/view?id='.$id.'&amp;'));
  temp::display('news.view');
  engine_fin();  
}
else
  denied();
  
