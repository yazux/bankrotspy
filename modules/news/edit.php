<?php
defined('DS_ENGINE') or die('web_demon laughs');


$id = intval(abs(GET('id')));
if(!$id) denied();

$res = core::$db->query('SELECT * FROM `ds_news` WHERE `id` = "'.$id.'" LIMIT 1;');
if($res->num_rows)
{
  $error = array();  
  $data = $res->fetch_assoc();
  if(CAN('news_edit', 0))
  {
    if(POST('submit'))
    {
      $nn_name = POST('newshead');
      if(!$nn_name)
       $error[] = lang('no_s_name');   
  
      $nn_text = POST('mess');
      if(!$nn_text)
        $error[] = lang('no_s_text');
    
      if(!$error)
      {
         core::$db->query('UPDATE `ds_news` SET 
          `name` = "'.core::$db->res($nn_name).'",
          `text` = "'.core::$db->res($nn_text).'"
          WHERE `id`="'.$id.'" LIMIT 1;');  
          
        uscache::rem('mess_head', lang('edit_st'));
        uscache::rem('mess_body', lang('stat_edited'));
         
        header('Location:'.core::$home.'/news/view?id='.$id);
        exit();
      }
    }
      
    engine_head(lang('edit_st'));  
    temp::assign('id', $data['id']);
    temp::HTMassign('error', $error);
    if(isset($nn_text))
      temp::assign('text', $nn_text);
    else
      temp::assign('text', $data['text']);

    if(isset($nn_name))
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