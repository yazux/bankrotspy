<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
{
   engine_head(lang('support')); 
   temp::display('support.index.unreg');
   engine_fin();
}

if(CAN('tech_support', 0))
{
   $res = core::$db->query('SELECT * FROM `ds_support` WHERE `closed` = "0" ORDER BY `newtime` DESC;');
   $arr = array();
   while($data = $res->fetch_assoc())
   {
     $out = array();
     $out['id'] =  $data['id'];
     $out['login'] =  $data['autor'];
     $out['read'] =  $data['read'];
     $out['usread'] =  $data['usread'];
     $out['userid'] =  $data['userid'];
     text::add_cache($data['cache']); 
     $out['text'] = text::out(text::auto_cut($data['text'], 200), 0).'...';
     $out['date'] = ds_time($data['time']);
     if($data['newtime'])
       $out['newtime'] = ds_time($data['newtime']);
    
     $arr[] = $out;  
   }

  $res = core::$db->query('SELECT * FROM `ds_support` WHERE `closed` = "1" ORDER BY `newtime` DESC;');
  $arr2 = array();
  while($data = $res->fetch_assoc())
  {
    $out = array();
    $out['id'] =  $data['id'];
    $out['login'] =  $data['autor'];
    $out['read'] =  $data['read'];
    $out['usread'] =  $data['usread'];
    $out['userid'] =  $data['userid'];
    text::add_cache($data['cache']);
    $out['text'] = text::out(text::auto_cut($data['text'], 200), 0).'...';
    $out['date'] = ds_time($data['time']);
    if($data['newtime'])
      $out['newtime'] = ds_time($data['newtime']);

    $arr2[] = $out;
  }

   engine_head(lang('support'));
   temp::HTMassign('narr', $arr);
   temp::HTMassign('narr2', $arr2);
   temp::display('support.index.admin');
   engine_fin();  
}
else
{
   $res = core::$db->query('SELECT * FROM `ds_support` WHERE `userid` = "'.core::$user_id.'" AND `closed` != "1" ORDER BY `newtime` DESC;');
   $arr = array();
   while($data = $res->fetch_assoc())
   {
     $out = array();
     $out['id'] =  $data['id'];
     $out['login'] =  $data['autor'];
     $out['read'] =  $data['read'];
     $out['usread'] =  $data['usread'];
     $out['userid'] =  $data['userid'];
     text::add_cache($data['cache']); 
     $out['text'] = text::out(text::auto_cut($data['text'], 200), 0).'...';
     $out['date'] = ds_time($data['time']);
     if($data['newtime'])
       $out['newtime'] = ds_time($data['newtime']);
    
     $arr[] = $out;  
   }
   
   $res = core::$db->query('SELECT * FROM `ds_support` WHERE `userid` = "'.core::$user_id.'" AND `closed` = "1" ORDER BY `newtime` DESC;');
   $arr2 = array();
   while($data = $res->fetch_assoc())
   {
     $out = array();
     $out['id'] =  $data['id'];
     $out['login'] =  $data['autor'];
     $out['userid'] =  $data['userid'];
     text::add_cache($data['cache']); 
     $out['text'] = text::out(text::auto_cut($data['text'], 200), 0).'...';
     $out['date'] = ds_time($data['time']);
     if($data['newtime'])
       $out['newtime'] = ds_time($data['newtime']);
    
     $arr2[] = $out;  
   }
    
   engine_head(lang('support'));
   temp::HTMassign('narr', $arr);
   temp::HTMassign('narr2', $arr2); 
   temp::display('support.index');
   engine_fin(); 
}