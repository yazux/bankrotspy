<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
if($id)
{
  $res = core::$db->query('SELECT * FROM `ds_article` WHERE `id` = "'.$id.'" LIMIT 1;');
  $res =  $res->fetch_assoc();
  if($res['id'])
  {
    if(POST('delete'))
    {
      core::$db->query('DELETE FROM `ds_article` WHERE `id` = "'.$id.'" LIMIT 1;');
      $at1 = core::$db->query('SELECT * FROM `ds_art_files` WHERE `post` = "'.$id.'";');
      if ($at1->num_rows)
      {
        while ($data = $at1->fetch_assoc())
          {
            if(file_exists('data/att_art/' .$data['filename'].'.dat'))
              unlink('data/att_art/' .$data['filename'].'.dat'); 
          }  
      }
      core::$db->query("DELETE FROM `ds_art_files` WHERE `post` = '".$id."';");  
      func::notify(lang('pr_adminpanel'), lang('stat_deleted'), core::$home.'/admin', lang('continue'));
    }  
      
    engine_head($res['name']);
    temp::assign('name', $res['name']);
    temp::assign('id', $res['id']);
    temp::display('admin.statdel');
    engine_fin();
  }
  else
    func::notify(lang('stat'), lang('stat_no'), core::$home, lang('continue'));
}
else
  func::notify(lang('stat'), lang('stat_no'), core::$home, lang('continue'));
 
