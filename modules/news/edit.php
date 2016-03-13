<?php
defined('DS_ENGINE') or die('web_demon laughs');


$id = intval(abs(GET('id')));
if(!$id) denied();

$save_data['newshead'] = $nn_name = POST('newshead');
$save_data['mess'] = $nn_text = POST('mess');
$save_data['keywords'] = $keywords = POST('keywords');
$save_data['description'] = $description = POST('description');

new fload(
  $save_data,                           //Указываем что сохранять
  core::$module,                        // Модуль 'articles'
  core::$module . '-' . core::$action,  //uid по нему восстанавливаются данные.
  core::$home . '/news/edit?id=' . $id,     // Устанавливаем куда возвращаться
  'edit',
  $id
);

if(isset($_POST['add_attachment']))
  fload::load_file();

if(isset($_POST['del_attachment']))
  fload::del_file(POST('del_attachment'));

$res = core::$db->query('SELECT * FROM `ds_news` WHERE `id` = "'.$id.'" LIMIT 1;');
if($res->num_rows)
{
  $error = array();  
  $data = $res->fetch_assoc();
  if(CAN('news_edit', 0))
  {
    if(POST('submit'))
    {
      //$nn_name = POST('newshead');
      if(!$nn_name)
       $error[] = lang('no_s_name');   
  
      //$nn_text = POST('mess');
      if(!$nn_text)
        $error[] = lang('no_s_text');
    
    //$keywords = POST('keywords');
    //$description = POST('description');
    
      if(!$error)
      {
            
          
         core::$db->query('UPDATE `ds_news` SET 
          `name` = "'.core::$db->res($nn_name).'",
          `text` = "'.core::$db->res($nn_text).'",
          `keywords` = "'.core::$db->res($keywords).'",
          `description` = "'.core::$db->res($description).'"
          WHERE `id`="'.$id.'" LIMIT 1;');  
          
        uscache::rem('mess_head', lang('edit_st'));
        uscache::rem('mess_body', lang('stat_edited'));
         
        header('Location:'.core::$home.'/news/view?id='.$id);
        exit();
      }
    }
      
    engine_head(lang('edit_st'));
    
    //Восстанавливаем данные
    $rem = array();
    if(uscache::ex('fload_edit_uinid') AND uscache::ex('fload_edit_save_data') AND (uscache::get('fload_edit_uinid') == core::$module . '-' . core::$action . '-' . $id))
    {
        $rem = unserialize(uscache::get('fload_edit_save_data'));
        
        if(isset($rem['newshead']))
            $nn_name = $rem['newshead'];
        if(isset($rem['mess']))
            $nn_text = $rem['mess'];
        if(isset($rem['keywords']))
            $keywords = $rem['keywords'];
        if(isset($rem['description']))
            $description = $rem['description'];
    }
    
    
    $out = fload::get_loaded();
    if($out)
        temp::assign('att_true', 1);
        temp::HTMassign('out', $out);
    
    temp::assign('id', $data['id']);
    temp::HTMassign('error', $error);
    
    if(!empty($nn_text))
      temp::assign('text', $nn_text);
    else
      temp::assign('text', $data['text']);

    if (!empty($keywords)) {
        temp::assign('keywords', $keywords);
    } else {
        temp::assign('keywords', $data['keywords']);
    }
  
    if (!empty($description)) {
        temp::assign('description', $description);
    } else {
        temp::assign('description', $data['description']);
    }
  
    if(!empty($nn_name))
      temp::assign('nick', $nn_name);
    else
      temp::assign('nick', $data['name']);
    temp::display('news.edit');
    engine_fin();  
  }
  else
    denied();
}
else
  denied();