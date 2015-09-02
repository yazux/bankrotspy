<?php
defined('DS_ENGINE') or die('web_demon laughs');

///////////////////////////////////////////////////////////////

function gename($string)
{
  $tr = array(
    "а"=>"a","б"=>"b",
    "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
    "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
    "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
    "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
    "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
    "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"_" );
  $len=mb_strlen($string);
  $out ='';
  $string=str_replace(' ','_',$string);
  $string=str_replace('\\','',$string);
  for($i=0;$i<=$len;$i++)
  {
    $text=mb_substr($string,$i,1);
      if (preg_match("/[0-9a-zа-я\-\_]/iu",$text))
      $out .= $text;
  }
  $out=mb_strtolower($out);
  $out=strtr($out,$tr);
  return $out;
}  

function cut_ext($string)
{
  $n=strrpos($string,".");
  $ext=substr($string,0,$n);
  return $ext;
}

///////////////////////////////////////////////////

$error = array();
$att = intval(abs(GET('att')));
if(isset($_POST['add_attachment']))
{
   $save_data = array();
   $save_data['art_name'] = htmlentities(POST('art_name'), ENT_QUOTES, 'UTF-8');
   $save_data['art_text'] = htmlentities(POST('art_text'), ENT_QUOTES, 'UTF-8');
   
   $file=$_FILES['file']['name'];
   if($file)
   {
     $ext=mb_strtolower(func::getextension($file));  
     if($ext)
     {
       $filename=gename(cut_ext($file));  
       if(file_exists('data/att_art/'.$filename.'.'.$ext.'.dat'))
       {  
         $num= file_get_contents('data/cache/count.dat')+1;
         $filename=$filename.'_'.$num;
         file_put_contents('data/cache/count.dat',$num);
       }
       move_uploaded_file($_FILES['file']['tmp_name'], 'data/att_art/'.$filename.'.'.$ext.'.dat');
       $at_num = $att;
       if (!$at_num)
         $at_num= file_get_contents('data/cache/post_count.dat')+1;
       file_put_contents('data/cache/post_count.dat',$at_num);
       core::$db->query('INSERT INTO `ds_art_files` SET
          `attach` = "'.$at_num.'",
          `time` = "'.time().'",
          `filename` = "'.core::$db->res($filename.'.'.$ext).'",
          `name` = "'.core::$db->res(htmlentities($file, ENT_QUOTES, 'UTF-8')).'",
          `count` = "0"');
       
       engine_head(lang('fileload'));
       temp::HTMassign('savevar', $save_data);
       temp::assign('link', core::$home.'/admin/add?att='.$at_num);
       temp::assign('name', lang('pr_article'));
       temp::assign('otext', lang('file_added'));
       temp::display('admin.statedit.fileload');
       engine_fin();   
     }  
     else
     {
       engine_head(lang('fileload'));
       temp::HTMassign('savevar', $save_data);
       temp::assign('link', core::$home.'/admin/add?att='.$att);
       temp::assign('name', lang('pr_article'));
       temp::assign('otext', lang('no_file'));
       temp::display('admin.statedit.fileload');
       engine_fin();  
     }  
   }
   else
   {
     engine_head(lang('fileload'));
     temp::HTMassign('savevar', $save_data);
     temp::assign('link', core::$home.'/admin/add?att='.$att);
     temp::assign('name', lang('pr_article'));
     temp::assign('otext', lang('no_file'));
     temp::display('admin.statedit.fileload');
     engine_fin();  
   }  
}
elseif(isset($_POST['del_attachment']))
{
  $save_data = array();
  $save_data['art_name'] = htmlentities(POST('art_name'), ENT_QUOTES, 'UTF-8');
  $save_data['art_text'] = htmlentities(POST('art_text'), ENT_QUOTES, 'UTF-8');
  
  $file_del = POST('del_attachment');
  $file_del = intval(abs(implode('',array_flip($file_del))));
  if ($file_del)
  {
    $req_file = core::$db->query("SELECT * FROM `ds_art_files` WHERE `id` = '".$file_del."' AND `attach` = '".$att."' LIMIT 1");  
    if($req_file->num_rows)
    {
      $fd = $req_file->fetch_assoc();
      core::$db->query("DELETE FROM `ds_art_files` WHERE `id` = '".$file_del."' LIMIT 1");
      if(file_exists('data/att_art/' . $fd['filename'].'.dat'))
        unlink('data/att_art/' . $fd['filename'].'.dat');
      
      engine_head(lang('fileload'));
      temp::HTMassign('savevar', $save_data);
      temp::assign('link', core::$home.'/admin/add?att='.$att);
      temp::assign('name', lang('pr_article'));
      temp::assign('otext', lang('file_deleted'));
      temp::display('admin.statedit.fileload');
      engine_fin();  
    }
    else
    {
      engine_head(lang('fileload'));
      temp::HTMassign('savevar', $save_data);
      temp::assign('link', core::$home.'/admin/add?att='.$att);
      temp::assign('name', lang('pr_article'));
      temp::assign('otext', lang('wrong_del'));
      temp::display('admin.statedit.fileload');
      engine_fin();  
    }  
  }
  else
  {
    engine_head(lang('fileload'));
    temp::HTMassign('savevar', $save_data);
    temp::assign('link', core::$home.'/admin/add?att='.$att);
    temp::assign('name', lang('pr_article'));
    temp::assign('otext', lang('wrong_del'));
    temp::display('admin.statedit.fileload');
    engine_fin();
  }
}
elseif(POST('submit'))
{
  $art_name = POST('art_name');
  if(!$art_name)
    $error[] = lang('no_name');
  
  $art_text = POST('art_text');
  if(!$art_text)
    $error[] = lang('no_text');
    
  if(!$error)
  {
    core::$db->query('INSERT INTO `ds_article` SET `name` = "'.core::$db->res($art_name).'", `text` = "'.core::$db->res($art_text).'", `cache` = "'.core::$db->res(text::presave($art_text)).'";'); 
    $insert_id = core::$db->insert_id;
    if ($att)
      core::$db->query('UPDATE `ds_art_files` SET `post` = "'.$insert_id.'" WHERE `attach` = "'.$att.'" AND `post` = "0";');
    func::notify(lang('pr_adminpanel'), lang('stat_added'), core::$home.'/article?id='.$insert_id, lang('continue'));
  }  
}

engine_head(lang('pr_adminpanel'));
$arr = array();
$out = array();
if($att)
{
  $at1 = core::$db->query('SELECT * FROM `ds_art_files` WHERE `attach` = "'.$att.'" ORDER BY `id`;');  
  if ($at1->num_rows)
  {
    temp::assign('att_true', 1);  
    
    while ($data = $at1->fetch_assoc())
    {
      $arr['id'] = $data['id'];
      $arr['name'] = $data['name'];
      $arr['filename'] = $data['filename'];
      $arr['nameraw'] = rawurlencode($data['name']);  
      if(text::is_image($data['name']))
        $arr['type'] = 'img';
      else
        $arr['type'] = 'file';  
      $out[] = $arr;  
    }  
  }  
}
if(POST('art_name'))
  temp::assign('name', POST('art_name')); 
if(POST('art_text'))
  temp::assign('text', POST('art_text'));
temp::HTMassign('out', $out);
temp::assign('att', $att);
temp::HTMassign('error', $error);
temp::display('admin.add');
engine_fin();

