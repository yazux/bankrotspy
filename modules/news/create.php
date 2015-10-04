<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(CAN('news_create', 0))
{
    
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
    
      uscache::rem('mess_head', lang('admin_news'));
      uscache::rem('mess_body', lang('news_succ'));
      header('Location: '.core::$home.'/news/view?id='.$insert_id);
      exit();
  }
}


engine_head(lang('admin_news'));
temp::HTMassign('error', $n_error);
temp::display('news.create');
engine_fin();

}
else
  denied();