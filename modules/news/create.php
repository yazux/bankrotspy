<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(CAN('news_create', 0))
{

$save_data = array();
$save_data['name'] = POST('newshead');
$save_data['text'] = POST('mess');
$save_data['keywords'] = POST('keywords');
$save_data['description'] = POST('description');

new fload(
  $save_data,                           //Указываем что сохранять
  core::$module,                        // Модуль 'articles'
  core::$module . '-' . core::$action,  //uid по нему восстанавливаются данные.
  core::$home . '/news/create',     // Устанавливаем куда возвращаться
  'create'
);

if(isset($_POST['add_attachment']))
  fload::load_file();

if(isset($_POST['del_attachment']))
  fload::del_file(POST('del_attachment'));

$n_error = array();

if(POST('submit'))
{
  $nn_name = POST('newshead');
  if(!$nn_name)
   $n_error[] = lang('no_s_name');   
  
  $nn_text = POST('mess');
  if(!$nn_text)
    $n_error[] = lang('no_s_text');
  
  $keywords = POST('keywords');
  $description = POST('description');
  
  if(!$n_error)
  {
    core::$db->query('INSERT INTO `ds_news` SET
       `name` = "'.core::$db->res($nn_name).'" ,
       `text` = "'.core::$db->res($nn_text).'" ,
       `keywords` = "'.core::$db->res($keywords).'" ,
       `description` = "'.core::$db->res($description).'" ,
       `cache`="'.core::$db->res(text::presave($description)).'",
       `user_id` = "'.core::$user_id.'",
       `login` = "'.core::$db->res(core::$user_name).'",
       `time` = "'.time().'";');
    
      $insert_id = core::$db->insert_id;
    
        fload::save_files($insert_id);
    
      uscache::rem('mess_head', lang('admin_news'));
      uscache::rem('mess_body', lang('news_succ'));
      header('Location: '.core::$home.'/news/view?id='.$insert_id);
      exit();
  }
}

engine_head(lang('admin_news'));
$out = fload::get_loaded();

if($out)
  temp::assign('att_true', 1);

$rem = array();
if(uscache::ex('fload_create_uinid') AND uscache::ex('fload_create_save_data') AND (uscache::get('fload_create_uinid') == core::$module . '-' . core::$action))
{
    $rem = unserialize(uscache::get('fload_create_save_data'));
  
    if(isset($rem['name']))
        temp::assign('name', $rem['name']);

    if(isset($rem['keywords']))
        temp::assign('keywords', $rem['keywords']);

    if(isset($rem['description']))
        temp::assign('description', $rem['description']);

    if(isset($rem['text']))
        temp::assign('text', $rem['text']);
}


temp::HTMassign('out', $out);
temp::HTMassign('error', $n_error);
temp::display('news.create');
engine_fin();

}
else
  denied();