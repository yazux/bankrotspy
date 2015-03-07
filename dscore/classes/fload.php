<?php
defined('DS_ENGINE') or die('web_demon laughs');

class fload
{
   private static $out_files = array();
   public static $return_link;
   public static $save_data = array();
   public static $att;
   public static $stat_id;
   public static $max_file_size = 5000;
   public static $max_files = 30;
   public static $module;
   public static $un_id;

   private static $max_width_web = 950; //px
   private static $max_height_web = 950; //px
    
   
   function __construct($save_data, $module, $un_id, $return_link, $mode, $id_stat = 0)
   {
      self::$module = $module;
      self::$un_id = $un_id;
      self::$return_link = $return_link;
      self::$att = uscache::get('att');
      self::$save_data = $save_data;
      if($mode == 'create')
      {
        if(!self::$att)
        {
          self::$att = intval(file_get_contents('data/cache/post_count.dat')) + 1;
          file_put_contents('data/cache/post_count.dat', self::$att, LOCK_EX);
          uscache::rem('att', self::$att);
        }
      }
      elseif($mode == 'edit')
      {
        self::$stat_id = intval(abs($id_stat));  
      }
   }
   
   public static function save_data()
   {
     if(self::$stat_id)
     {
       uscache::rem('fload_edit_postid', self::$stat_id);
       uscache::rem('fload_edit_uinid', self::$un_id);  
       uscache::rem('fload_edit_save_data', serialize(self::$save_data));  
     }
     else
     {
       uscache::rem('fload_create_uinid', self::$un_id);  
       uscache::rem('fload_create_save_data', serialize(self::$save_data));  
     }  
   }
   
   /**
   * Получение файлов из базы для определенной статьи $id
   * 
   * @param int $id
   * @param int module
   * @param string
   * @return boolean
   */ 
   private static function get_files($id, $module, $att_sr = '')
   {
     if($att_sr)
       $fil1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `attach` = "'.$id.'" AND `module` = "'.$module.'";');
     else  
       $fil1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `post` = "'.$id.'" AND `module` = "'.$module.'";');
     while ($fil = $fil1->fetch_assoc())
     {
       $out_fil[$fil['name']] = array();
       $out_fil[$fil['name']]['filename'] = $fil['name'];
       $out_fil[$fil['name']]['name'] = $fil['name'];
       $out_fil[$fil['name']]['id'] = $fil['id'];
       self::$out_files = $out_fil;  
     }
     return TRUE;
   }
   
   /**
   * Определение типа картинки
   * 
   *  Возвращет true если картинки изображение
   *  Иначе - false
   * 
   * @param string $string  
   * @return boolean
   */
   public static function is_image($string)
   {
     $exts = array('png', 'jpg', 'gif', 'jpeg');  
     if(in_array(func::getextension($string),$exts))
       return TRUE;
     else
       return FALSE;
   }
   
   /**
   * Парсим теги в строке $data регуляркой и передаем в image_replace
   * 
   * @param string $data
   * @param int $id
   * @param module
   * @param string
   * @return string
   */
   static function replace_files($data, $id, $module, $att_sr =  '')
   {
     self::get_files($id, $module, $att_sr);  
     $to_text = preg_replace_callback('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', array('self', 'file_replace'), $data);  
     return $to_text;  
   } 
   
   /**
   * Парсим теги в строке $data регуляркой и передаем в image_replace
   * 
   * @param string $data
   * @param array $arr_files = array()
   * @return string
   */
   static function replace_files_arr($data, $arr_files = array())
   {
     self::$out_files = $arr_files; 
     $to_text = preg_replace_callback('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', array('self', 'file_replace'), $data);  
     return $to_text;  
   }
    
   public static function file_replace($mach)
   {
     ///////////////////////////////////////////////
     // Заменяем теги на картнки и ссылки на файлы
     ///////////////////////////////////////////////
     $out_fil = self::$out_files;
     $home = core::$home;  
     $item = $mach[2];
     
     $text = $mach[3];  
     if($mach[1]=='file')
     {
       $item = trim($item);
        preg_match('/\?([0-9]{0,4})x([0-9]{0,4})$/',$item,$maches);
        if (isset($maches[0]))
          $item=str_replace($maches[0],'',$item);
        if(!$text)
          $text = $out_fil[$item]['name'];
        return '<a title="'.$text.'" href="'.$home.'/load/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><i class="icon-attach"></i> '.$text.'</a>';
     }
     elseif($mach[1]=='img')
     {
        $align='';
        if(preg_match('/^ ([^\s]{1,}) $/',$item))
          $align='none_float';  
        elseif(preg_match('/^ ([^\s]{1,})$/',$item))
          $align='right_float';    
        elseif(preg_match('/^([^\s]{1,}) $/',$item))
          $align='left_float';
        $item = trim($item); 

        preg_match('/\?([0-9]{0,4})x([0-9]{0,4})$/',$item,$maches);
        if (isset($maches[0]))
        {
          $item=str_replace($maches[0],'',$item);
          $w= $maches[1];
          $h= $maches[2];
        }
        
        $it_ext = func::getextension($item);
        if(isset($out_fil[$item]))
        {
          if(self::is_image($item))
          {
             if(core::$typetheme == 'web')
             {
               $maxh= self::$max_height_web;
               $maxw= self::$max_width_web;
             }
             else
             {
                $maxh=250;
                $maxw= 250; 
             } 
             $size= getimagesize('data/att_post/'.$out_fil[$item]['id'].'.dat');
             //определяемся с размерами
             //Эта хрень работает, нехуй разбираться
             if(!isset($h) and !isset($w)) {
              $h = $size[1];
              $w = $size[0];
              if ($h>=$w and $h>= $maxh) {
                 $w=$w*$maxh/$h;
                 $h=$maxh; 
              }
              elseif($w>$h and $w> $maxw) {
                  $h=$h*$maxw/$w;
                  $w=$maxh;             
              }
            } 
            if($h and !$w) {
               if($h > $maxh){
                 $w=$size[0]*$maxh/$size[1];
                 $h=$maxh; 
               }
               else
                 $w=$size[0]*$h/$size[1];  
            }
            elseif($w and !$h) {
               if($w > $maxw){
                 $h=$size[1]*$maxw/$size[0];
                 $w=$maxw; 
               }
               else
                 $h=$size[1]*$w/$size[0];
             }    
            else {
              if ($h>$w and $h> $maxh) {
                 $w=$w*$maxh/$h;
                 $h=$maxh; 
              }
              elseif($w>$h and $w> $maxw) {
                 $h=$h*$maxw/$w;
                 $w=$maxh;             
              }
            }
            
            $h=ceil($h);
            $w=ceil($w);
            if(file_exists('data/att_img/'.$out_fil[$item]['id'].'.'.$w.'x'.$h.'.'.$it_ext))
              return '<a target="_blank" title="'.$text.'" href="'.$home.'/load/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><img class="insimg" '.($align ? 'class="'.$align.'"' : '').' alt="'.$text.'"  src="'.$home.'/data/att_img/'.$out_fil[$item]['id'].'.'.$w.'x'.$h.'.'.$it_ext.'"/></a>';
            else  
              return '<a target="_blank" alt="'.$text.'" href="'.$home.'/load/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><img class="insimg" '.($align ? 'class="'.$align.'"' : '').'  title="'.$text.'" src="'.$home.'/small/img.php?i='.$out_fil[$item]['id'].'.'.$it_ext.''.($w ? '&amp;w='.$w : '').''.($h ? '&amp;h='.$h : '').'" /></a>';
          }   
          else
           return $mach[0]; 
        } 
        else
         return $mach[0]; 
     }
     else
       return $mach[0]; 
   }

   static function gename($string)
   {
     $tr = array(
       '\\' => '',
       '/' => '',
       ':' => '',
       '*' => '',
       '?' => '',
       ']' => '',
       '[' => '',
       '"' => '',
       '>' => '',
       '<' => '',
       '<' => '', 
     );

     $out=strtr($string,$tr);
     return $out;
   }

   static function message($text)
   {
     ///////////////////////////////////////////////
     //  И так ясно
     ///////////////////////////////////////////////
     self::save_data();
     //uscache::rem('message_article', $text);

     uscache::rem('mess_head', 'File Load');
     uscache::rem('mess_body', $text);

     header('Location: '.self::$return_link);
     exit(); 
   }

   static function files_att()
   {
     $fil1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `attach` = "'.self::$att.'" AND `module` = "'.self::$module.'";');
     $out_fil = array();
     while ($fil = $fil1->fetch_assoc())
     {
       $out_fil[] = $fil['name'];
     }
     return $out_fil; 
   }

   static function load_file()
   {
     ///////////////////////////////////////////////
     // Загружем файл
     ///////////////////////////////////////////////  
     $file=$_FILES['file']['name'];
     if($file)
     {   
         $fname=self::gename($file);
         
         if(ceil($_FILES['file']['size']/1024) > self::$max_file_size)
           self::message(lang('file_too_big_now').' <b>'.self::$max_file_size.'</b> '.lang('kb'));  //файл слишком большой
       
         $loaded_files =  self::files_att();
         if(in_array($fname,$loaded_files))
           self::message(lang('file_p_one').' <b>'.htmlentities($fname, ENT_QUOTES, 'UTF-8').'</b> '.lang('file_p_two'));  //файл существует
         
         if(count($loaded_files) >= self::$max_files)
           self::message(lang('too_much_files').' <b>'.self::$max_files.'</b> '.lang('too_much_files_2'));  //Слишком много файлов
       
         if(self::$stat_id)
         {
           core::$db->query('INSERT INTO `ds_post_files` SET
            `userload` = "'.core::$user_id.'",
            `module` = "'.self::$module.'",
            `post` = "'.self::$stat_id.'",
            `attach` = "0",
            `time` = "'.time().'",
            `name` = "'.core::$db->res(htmlentities($fname, ENT_QUOTES, 'UTF-8')).'",
            `count` = "0"');
         }
         else
         {
           core::$db->query('INSERT INTO `ds_post_files` SET
            `userload` = "'.core::$user_id.'",
            `module` = "'.self::$module.'",
            `attach` = "'.self::$att.'",
            `time` = "'.time().'",
            `name` = "'.core::$db->res(htmlentities($fname, ENT_QUOTES, 'UTF-8')).'",
            `count` = "0"');
         }
          
         $filename = core::$db->insert_id;
         move_uploaded_file($_FILES['file']['tmp_name'], 'data/att_post/'.$filename.'.dat');
         self::message(lang('file_added'));   
  
     }
     else
       self::message(lang('no_file'));
   }

   static function del_file($file_del)
   {
      ///////////////////////////////////////////////
      // Удаляем файл
      /////////////////////////////////////////////// 
      $file_del = intval(abs(implode('',array_flip($file_del))));
      if ($file_del)
      {
        if(self::$stat_id)  
          $req_file = core::$db->query("SELECT * FROM `ds_post_files` WHERE `id` = '".$file_del."' AND `post` = '".self::$stat_id."' AND `module` = '".self::$module."' LIMIT 1");  
        else
          $req_file = core::$db->query("SELECT * FROM `ds_post_files` WHERE `id` = '".$file_del."' AND `attach` = '".self::$att."' AND `module` = '".self::$module."' LIMIT 1"); 
        
        if($req_file->num_rows)
        {
          $fd = $req_file->fetch_assoc();
          core::$db->query("DELETE FROM `ds_post_files` WHERE `id` = '".$file_del."' LIMIT 1");
          if(file_exists('data/att_post/' . $fd['id'].'.dat'))
            unlink('data/att_post/' . $fd['id'].'.dat');
          self::message(lang('file_deleted'));  
        }
        else
          self::message(lang('wrong_del'));
      }
      else
        self::message(lang('wrong_del'));
   }


   static function get_loaded()
   {
     ///////////////////////////////////////////////
     // Возвращает массив с загруженными файлами
     ///////////////////////////////////////////////  
     $arr = array();
     $out = array();
     if(self::$att OR self::$stat_id)
     {
       if(self::$stat_id)
         $at1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `post` = "'.self::$stat_id.'" AND `module` = "'.self::$module.'" ORDER BY `id`;');
       else
         $at1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `attach` = "'.self::$att.'" AND `module` = "'.self::$module.'" ORDER BY `id`;');  
       if ($at1->num_rows)
       {
         while ($data = $at1->fetch_assoc())
         {
           $arr['id'] = $data['id'];
           $arr['name'] = $data['name'];
           $arr['filename'] = $data['name'];
           $arr['nameraw'] = rawurlencode($data['name']);  
           if(fload::is_image($data['name']))
             $arr['type'] = 'img';
           else
             $arr['type'] = 'file';  
           $out[] = $arr;  
         }  
       }  
     }
     return $out;  
   }

   static function save_files($insert_id = 0)
   {
     //Выполняется при сохранении новой статьи. 
     if(self::$stat_id)
     {
       uscache::del('message_article');
       uscache::del('fload_edit_postid');
       uscache::del('fload_edit_uinid');  
       uscache::del('fload_edit_save_data');
     }
     else
     {
       if(self::$att)
         core::$db->query('UPDATE `ds_post_files` SET `post` = "'.$insert_id.'" WHERE `attach` = "'.self::$att.'" AND `module` = "'.self::$module.'" AND `post` = "0";');
       uscache::del('att');
       uscache::del('message_article');
       uscache::del('fload_create_uinid');  
       uscache::del('fload_create_save_data');
       self::$att = '';
     }
   }
   
   function del_files($id_stat, $module = '')
   {
     $at1 = core::$db->query('SELECT * FROM `ds_post_files` WHERE `post` = "'.$id_stat.'" AND `module` = "'.$module.'";');
     if ($at1->num_rows)
     {
       while ($data = $at1->fetch_assoc())
       {
         if(file_exists('data/att_post/' .$data['id'].'.dat'))
           unlink('data/att_post/' .$data['id'].'.dat'); 
       }  
     }  
     core::$db->query("DELETE FROM `ds_post_files` WHERE `post` = '".$id_stat."' AND `module` = '".$module."';");
     return TRUE;  
   } 
}



