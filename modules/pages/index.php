<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
if($id)
{
  $res = core::$db->query('SELECT * FROM `ds_pages` WHERE `id` = "'.$id.'" LIMIT 1;');
  $res =  $res->fetch_assoc();
  if($res['id'])
  {
    text::add_cache($res['cache']);  
    
    $fil1 = core::$db->query('SELECT * FROM `ds_pages_files` WHERE `post` = "'.$id.'";');
    while ($fil = $fil1->fetch_assoc())
    {
      $out_fil[$fil['filename']] = array();
      $out_fil[$fil['filename']]['filename'] = $fil['filename'];
      $out_fil[$fil['filename']]['name'] = $fil['name'];
      $out_fil[$fil['filename']]['id'] = $fil['id'];  
    }
    
    if(isset($out_fil))
      pages::$out_fil = $out_fil;
      
    engine_head($res['name'], $res['keywords'], $res['description']);
    
    temp::assign('name', $res['name']);
    temp::assign('id', $res['id']);
    $to_text = text::out($res['text'] ,0);
    $to_text = preg_replace_callback('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', array('pages', 'image_replace'), $to_text);
    temp::HTMassign('text',$to_text);
    if(core::$rights == 100)
      temp::assign('r_edit', 1);
    temp::display('pages.index');
    engine_fin();
  }
  else
    denied();
}
else
  denied();








