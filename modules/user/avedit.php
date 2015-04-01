<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = intval(abs(GET('id')));
$error = array();
if($id and core::$user_id)
{
  $res = core::$db->query('SELECT * FROM  `ds_users` WHERE `id` = "'.core::$db->res($id).'" LIMIT 1');  
  if($res->num_rows)
  {
    $data = $res->fetch_array();
    if(CAN('edit_user', $data['rights']) or core::$user_id == $id)
    {
      if(POST('load'))
      {
         $array = array('png', 'gif', 'jpg'); 
         if(empty($_FILES['file']['name']))
           $error[] = lang('no_file');
         elseif(!in_array(func::getextension($_FILES['file']['name']),$array))
           $error[] = lang('err_format');
         else
         {
           $size=ceil($_FILES['file']['size']/1024);  
           if($size > core::$set['max_av_size'])  
             $error[] = lang('file_too_big').' '.core::$set['max_av_size'].' '.lang('max_kb');
         }
         
         if(!$error)
         {
           $image = $_FILES['file']['tmp_name'];  
           $size= @getimagesize($image);
           if(empty($size[1]) or empty($size[0]))
             $error[] = lang('img_not_valid');
           else
           {
             $maxh = 70;
             $maxw = 70;
               
             $h = $size[1];
             $w = $size[0];  
             if ($h>=$w and $h>= $maxh)
             {
               $w=ceil($w*$maxh/$h);
               $h=$maxh; 
             }
             elseif($w>$h and $w> $maxw)
             {
               $h=ceil($h*$maxw/$w);
               $w=$maxh;             
             }
             
             $file_ext = func::getextension($_FILES['file']['name']);
             if($file_ext=='gif')
               $img = @imagecreatefromgif($image);
             elseif($file_ext=='jpeg')
               $img = @imagecreatefromjpeg($image);
             elseif($file_ext=='jpg')
               $img = @imagecreatefromjpeg($image);
             elseif($file_ext=='png')
               $img = @imagecreatefrompng($image);  
             
             if(!$img)
               $error[] = lang('img_not_valid2');
             else
             {
               $av_big=ImageCreateTrueColor($w,$h);  
               imagealphablending($av_big, false);
               imagesavealpha($av_big,true);  
               $transparent = imagecolorallocatealpha($av_big, 255, 255, 255, 127);
               imagefilledrectangle($av_big, 0, 0, $w, $h, $transparent);
               imagecopyresampled($av_big, $img, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);  
               
               $av_small=ImageCreateTrueColor(40,40);  
               imagealphablending($av_small, false);
               imagesavealpha($av_small,true);  
               $transparent = imagecolorallocatealpha($av_small, 255, 255, 255, 127);
               imagefilledrectangle($av_small, 0, 0, 40, 40, $transparent);
               imagecopyresampled($av_small, $img, 0, 0, 0, 0, 40, 40, $size[0], $size[1]);
             }
           }   
         }
         
         if(!$error)
         {
             
           if(file_exists('images/avatars/'.$data['avtime'].'_'.$id.'_small.png'))
             unlink('images/avatars/'.$data['avtime'].'_'.$id.'_small.png');
           
           if(file_exists('images/avatars/'.$data['avtime'].'_'.$id.'.png'))
             unlink('images/avatars/'.$data['avtime'].'_'.$id.'.png');
             
           $avtime = time();  
           imagepng($av_big,'images/avatars/'.$avtime.'_'.$id.'.png');  
           imagepng($av_small,'images/avatars/'.$avtime.'_'.$id.'_small.png');
           core::$db->query('UPDATE `ds_users` SET `avtime`="'.$avtime.'" WHERE `id` = "'.$id.'";');  
           func::notify(lang('anketa').' | '.lang('avatar') , lang('av_loaded'), core::$home.'/user/profile?id='.$id);  
         }  
      }
      elseif(POST('delete'))
      {
         if(file_exists('images/avatars/'.$data['avtime'].'_'.$id.'_small.png'))
           unlink('images/avatars/'.$data['avtime'].'_'.$id.'_small.png');
           
         if(file_exists('images/avatars/'.$data['avtime'].'_'.$id.'.png'))
           unlink('images/avatars/'.$data['avtime'].'_'.$id.'.png');
          
         func::notify(lang('anketa').' | '.lang('avatar') , lang('av_deleted'), core::$home.'/user/avedit?id='.$id);
      }  

      $avatar = user::get_avatar($id, $data['avtime']);
        
      engine_head(lang('anketa').' | '.lang('avatar'));
      temp::assign('user_prof',$id);
      temp::assign('avatar',$avatar);
      temp::HTMassign('error',$error);
      
      temp::display('user.avedit');
      engine_fin(); 
    }
    else
      denied();
  }
  else
    denied();  
}
else
  denied();