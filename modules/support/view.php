<?php
defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

//$save_data = array();
//$save_data['msg'] = POST('msg');

//new fload(
//    $save_data,                           //Указываем что сохранять
//    core::$module,                        // Модуль 'support'
//    core::$module . '-' . core::$action,  //uid по нему восстанавливаются данные.
//    core::$home . '/support/view',     // Устанавливаем куда возвращаться
//    'create'
//);

//if(isset($_POST['add_attachment'])){
//    fload::load_file();
//    $id = (int)$_POST['id'];
//}

$id = (int)$_POST['id'];

if ( !$id ) {
    $id = (int)$_GET['id'];
}

//var_dump($_POST);die();
if( !$id ) {
    denied();
}

$res = core::$db->query('SELECT `ds_support`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights` FROM `ds_support` LEFT JOIN `ds_users` ON `ds_support`.`userid` = `ds_users`.`id` WHERE `ds_support`.`id` = "'.$id.'" LIMIT 1;');

if($res->num_rows) {
    
    $rs = $res->fetch_assoc();
    if(core::$user_id != $rs['userid'] AND !CAN('tech_support', 0)) {
        denied();
    }
  
    new nav(100); //Постраничная навигация
  
    new comm(core::$module, core::$action, $id); //Класс для работы с камментами
  
    $total = comm::total();

    // Добавление сообщения (комментария)
    if(POST('submit')) {
        if(core::$user_id AND CAN('create_comm', 0) AND !$rs['closed']) {
            
            $post = POST('msg');
            $error = comm::check_post($post);
        
            if(!$error) {
                
                $warn[] = lang('comm_added');

                //Добавляем пост
                if(CAN('tech_support', 0)) {
                    core::$db->query('UPDATE `ds_support` SET `newtime` = "' . time() . '", `read` = "1", `usread` = "0" WHERE `id` = "' . $id . '";'); 
                } else {
                    core::$db->query('UPDATE `ds_support` SET `newtime` = "' . time() . '", `read` = "0", `usread` = "0" WHERE `id` = "' . $id . '";'); 
                }
            
                comm::add_post($post, 1);
        
                uscache::rem('mess_head', lang('support'));
                uscache::rem('mess_body', lang('mess_body'));

                header('Location: ' . core::$home.'/support');
                exit();
            } else {
                uscache::rem('mess_head', lang('lng_err'));
                uscache::rem('mess_body', implode('<br/>', $error));

                header('Location: ' . core::$home.'/support/view?id='.$id.'');
                exit();  
            }
        } else
            denied();
    }
  
    if(core::$user_id == $rs['userid']) {
        if( $rs['read'] == 1 AND $rs['usread'] == 0 ) {
            core::$db->query('UPDATE `ds_support` SET `usread` = "1" WHERE `id` = "' . $id . '";');
        }
    }
  
    if(file_exists('images/avatars/'.$rs['avtime'].'_'.$rs['userid'].'_small.png'))
        $avatar =  '/images/avatars/'.$rs['avtime'].'_'.$rs['userid'].'_small.png';
    else
        $avatar = '';
  
    // Выводим комментарии
    $comms = comm::view(nav::$start, nav::$kmess);

    engine_head(lang('support'));
 
    temp::assign('avatar', $avatar);
    temp::assign('us_tech_online', user::is_online($rs['lastvisit']));
    temp::HTMassign('error', $error);
    temp::assign('id', $id);
    temp::HTMassign('com',$comms);
    temp::assign('total',$total);
    
    if(core::$user_id and CAN('create_comm', 0))
        temp::assign('its_user',core::$user_id);
    
    temp::HTMassign('text', text::out($rs['text'], 0));
    temp::assign('time', ds_time($rs['time']));
    temp::assign('login', $rs['autor']);
    temp::assign('userid', $rs['userid']);
    temp::assign('tech_close', $rs['closed']);
    temp::HTMassign('navigation', nav::display($total, core::$home.'/support/view?id='.$id.'&amp;'));
    temp::display('support.view');
    
    //Получаем список загруженных файлов
    $out = fload::get_loaded();
    if($out)
        temp::assign('att_true', 1);

    engine_fin();
    
} else
    denied();  
  


