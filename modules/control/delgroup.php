<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(GET('id')));
if(!$id)
  denied();

if($id > 99 OR $id < 1)
  denied();
  
$twr = core::$db->query('SELECT * FROM `ds_rights` WHERE `id` = "'.$id.'";');
if($twr->num_rows)
{
  if(POST('submit'))  
  {  
    core::$db->query('DELETE FROM `ds_rights` WHERE `id`="'.$id.'";');
    $rfile = core::parse_lang('data/lang_rights/rights.lang');
    unset($rfile['long_'.$id]); 
    unset($rfile['short_'.$id]);
    $outfile = '';
    foreach($rfile AS $key=>$value)
    {
      $outfile .= $key.' = '.$value."\n";     
    }
    file_put_contents('data/lang_rights/rights.lang',$outfile,LOCK_EX);
    core::$db->query('UPDATE `ds_users` SET `rights` = "0" WHERE `rights`="'.$id.'";');
    
    uscache::rem('mess_head', lang('delete_st'));
    uscache::rem('mess_body', lang('stat_deleted'));
    
    header('Location:'.core::$home.'/control/rights');
    exit();
  }
  else
    denied();  
}
else
  denied();

