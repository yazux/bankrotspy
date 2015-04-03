<?php
defined('DS_ENGINE') or die('web_demon laughs');
//Для класса с камментами нужен id, создаем его искуственно
$id = 1;
$error = array();

new nav(); //Постраничная навигация

$res = core::$db->query('SELECT * FROM `ds_feedback` WHERE `id` = "'.$id.'" LIMIT 1;');
$rs = $res->fetch_assoc();  

new comm(core::$module, core::$action, $id); //Класс для работы с камментами
comm::$sort = 1; //задаем обратную сортировку

$total = comm::total();

  if(POST('submit'))
  {
    if(core::$user_id AND CAN('create_comm', 0))
    {   
      $post = POST('mess');
      $error = comm::check_post($post);
        
      if(!$error)
      {
        $warn[] = lang('comm_added');
      
        //Добавляем пост    
        comm::add_post($post);
        //Обьявляем всем что в статье есть новые комменты
        core::$db->query('UPDATE `ds_feedback` SET `comtime` = "' . time() . '" WHERE `id` = "' . $id . '";');
        //Пользователю добавившему пост автоматом защитываем просмотр
        $comr = core::$db->query('SELECT * FROM `ds_feedback_comm_rdm` WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";');
        if(!$comr->num_rows)
          core::$db->query('INSERT INTO `ds_feedback_comm_rdm` SET `modid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `rdmtime` = "'.time().'";');    
        else
          core::$db->query('UPDATE `ds_feedback_comm_rdm` SET `rdmtime` = "'.time().'" WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";');
        
        uscache::rem('mess_head', lang('mess_head'));
        uscache::rem('mess_body', lang('mess_body'));
         
        header('Location: ' . core::$home.'/feedback?page='.comm::$page.'#comms');
        exit();
      }
      else
      {
        uscache::rem('mess_head', lang('lng_err'));
        uscache::rem('mess_body', implode('<br/>', $error));
        
        header('Location: ' . core::$home.'/feedback/write');
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
       $us_query .= 'SELECT * FROM `ds_feedback_rdm` WHERE `artid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
     if($rs['comtime'] > ($nowtime - (3 * 24 * 3600)))
       $us_query .= 'SELECT * FROM `ds_feedback_comm_rdm` WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
     
     if($us_query)
     {
       $fin_sql = '';
       core::$db->multi_query($us_query);
       if($rs['time'] > ($nowtime - (3 * 24 * 3600)))
       {
         $creq = core::$db->store_result();
         if(!$creq->num_rows)
           $fin_sql .= 'INSERT INTO `ds_feedback_rdm` SET `artid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `time` = "'.time().'";';    
       }
       
       if(($rs['time'] > ($nowtime - (3 * 24 * 3600))) AND ($rs['comtime'] > ($nowtime - (3 * 24 * 3600))))
         core::$db->next_result();
       
       if($rs['comtime'] > ($nowtime - (3 * 24 * 3600)))
       {
         $creq = core::$db->store_result();
         if(!$creq->num_rows)
           $fin_sql .= 'INSERT INTO `ds_feedback_comm_rdm` SET `modid` = "'.$rs['id'].'", `userid` = "'.core::$user_id.'", `rdmtime` = "'.time().'";';  
         else
         {
           $cdata = $creq->fetch_array();
           if($rs['comtime'] > $cdata['rdmtime']) 
             $fin_sql .= 'UPDATE `ds_feedback_comm_rdm` SET `rdmtime` = "'.time().'" WHERE `modid` = "'.$rs['id'].'" AND `userid` = "'.core::$user_id.'";';
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
  
  
  engine_head(lang('feed'));
  temp::HTMassign('error', $error);
  temp::assign('id', $id);
  temp::assign('not_need_quotes', 1);
  temp::HTMassign('com',$comms);
  temp::assign('total',$total);
  if(core::$user_id and CAN('create_comm', 0))
      temp::assign('its_user',core::$user_id);
  temp::HTMassign('navigation', nav::display($total, core::$home.'/feedback?'));    
  temp::display('feedback.index');
  engine_fin();
  
  
  
  