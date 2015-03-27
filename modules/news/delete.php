<?php
defined('DS_ENGINE') or die('web_demon laughs');


$id = intval(abs(GET('id')));
if(!$id) denied();

$res = core::$db->query('SELECT * FROM `ds_news` WHERE `id` = "'.$id.'" LIMIT 1;');
if($res->num_rows)
{
  $data = $res->fetch_assoc();
  if(CAN('news_delete', 0))
  {
    if(POST('submit'))
    {
      core::$db->query('DELETE FROM `ds_news` WHERE `id`="'.$id.'" LIMIT 1;');

      uscache::rem('mess_head', lang('delete_st'));
      uscache::rem('mess_body', lang('stat_deleted'));
         
      header('Location:'.core::$home.'/news');
      exit();
    }
    else
      denied();
  }
  else
    denied();
}
else
  denied();
