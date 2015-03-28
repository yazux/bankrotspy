<?php
defined('DS_ENGINE') or die('web_demon laughs');
$error = array();

$names_r =  core::parse_lang('data/lang_rights/descr.lang');
$res = core::$db->query('SELECT * FROM `ds_rights` WHERE `id` = "100";');
$data = $res->fetch_array();
$descr = unserialize($data['rights']);
$all_desk = array();
$all_regs = array(); 
  
foreach ($descr AS $key=>$value)
{
  $all_desk[$key] = $names_r[$key];
  $all_regs[] = $key;  
}

$res = core::$db->query('SELECT * FROM `ds_rights` ORDER BY `id` DESC;');
$rmenu = array();
$all_ids = array();
while($dat = $res->fetch_array())
{
  $all_ids[] = $dat['id'];
}

if(POST('submit'))
{
  $name = POST('gr_fullname');  
  if(!$name)
    $error[] = lang('no_full_name');  
  
  $sname = POST('gr_shortname');
  if(!$sname)  
    $error[] = lang('no_sr_name');
  elseif(mb_strlen($sname) > 5) 
    $error[] = lang('max_sr_name');
    
  $sid = abs(intval(POST('gr_id')));
  if(!$sid)
    $error[] = lang('no_id_name');
  elseif($sid > 99 OR $sid < 1)
    $error[] = lang('wr_id_name');
  elseif(in_array($sid, $all_ids))
    $error[] = lang('wr_id_ren');
    
   $regime = POST('rights');
   $new_regime = array();
   if(!$regime OR !is_array($regime))
   {
     $error[] = lang('no_rights');
     $regime = $new_regime;
   }
   else
   {
     $loc_err = false;  
     foreach($regime AS $value)
     {
       if(!in_array($value, $all_regs))
         $loc_err = true;
       else
         $new_regime[] = $value;
     }
     if($loc_err)
       $error[] = lang('wrong_rights');
     $regime = $new_regime;        
   } 
    
   if(!$error)
   {
      $rfile = core::parse_lang('data/lang_rights/rights.lang');
      $rfile['long_'.$sid] = text::st($name); 
      $rfile['short_'.$sid] = text::st($sname); 
      
      $outfile = '';
      foreach($rfile AS $key=>$value)
      {
        $outfile .= $key.' = '.$value."\n";  
          
      }
      
      $out_right = array();
      foreach($regime AS $skey=>$svalue)
      {
        $out_right[$svalue] = 1;    
      }
      
      file_put_contents('data/lang_rights/rights.lang',$outfile,LOCK_EX);
      
       core::$db->query('INSERT INTO `ds_rights` SET
       `id` = "'.$sid.'" ,
       `rights` = "'.core::$db->res(serialize($out_right)).'";');
       
     uscache::rem('mess_head', lang('mess_hesd'));
     uscache::rem('mess_body', lang('mess_body'));
        
     header('Location:'.core::$home.'/control/rights');
     exit(); 
   }  
}

engine_head(lang('admin_control'));
if(isset($name))
  temp::assign('name', $name);
if(isset($sname))
  temp::assign('sname', $sname);
if(isset($sid))
  temp::assign('sid', $sid);    

temp::HTMassign('error', $error);
temp::HTMassign('regm', $all_desk);
if(POST('submit'))
  temp::HTMassign('nowreg', $regime);
else
  temp::HTMassign('nowreg', array());

temp::display('control.creategroup');
engine_fin();