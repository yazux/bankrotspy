<?php
defined('DS_ENGINE') or die('web_demon laughs');

class pages
{
  public static $out_fil;

  public static function is_image($string)
  {
    $exts = array('png', 'jpg', 'gif', 'jpeg');
    if(in_array(func::getextension($string),$exts))
      return TRUE;
    else
      return FALSE;
  }

  public static function image_replace($mach)
  {
    $out_fil = self::$out_fil;
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
      return '<a title="'.$text.'" href="'.$home.'/article/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><i class="icon-doc-inv"></i> '.$text.'</a>';
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

      if(isset($out_fil[$item]))
      {

        if(self::is_image($item))
        {
          $maxh=550;
          $maxw= 550;
          $size= getimagesize('data/att_art/'.$item.'.dat');
          //определяемся с размерами

          if(!isset($h) and !isset($w))
          {
            $h = $size[1];
            $w = $size[0];
            if ($h>=$w and $h>= $maxh)
            {
              $w=$w*$maxh/$h;
              $h=$maxh;
            }
            elseif($w>$h and $w> $maxw)
            {
              $h=$h*$maxw/$w;
              $w=$maxh;
            }
          }
          if($h and !$w)
          {
            if($h > $maxh)
            {
              $w=$size[0]*$maxh/$size[1];
              $h=$maxh;
            }
            else
              $w=$size[0]*$h/$size[1];
          }
          elseif($w and !$h)
          {
            if($w > $maxw)
            {
              $h=$size[1]*$maxw/$size[0];
              $w=$maxw;
            }
            else
              $h=$size[1]*$w/$size[0];
          }
          else
          {
            if ($h>$w and $h> $maxh)
            {
              $w=$w*$maxh/$h;
              $h=$maxh;
            }
            elseif($w>$h and $w> $maxw)
            {
              $h=$h*$maxw/$w;
              $w=$maxh;
            }
          }

          $h=ceil($h);
          $w=ceil($w);

          if(file_exists('data/att_img/'.$item.'.'.$w.'x'.$h.'.png'))
            return '<a title="'.$text.'" href="'.$home.'/pages/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><img '.($align ? 'class="'.$align.'"' : '').' alt="'.$text.'"  src="'.$home.'/data/att_img/'.$item.'.'.$w.'x'.$h.'.png"/></a>';
          else
            return '<a alt="'.$text.'" href="'.$home.'/pages/file'.$out_fil[$item]['id'].'/'.rawurlencode($out_fil[$item]['name']).'"><img '.($align ? 'class="'.$align.'"' : '').'  title="'.$text.'" src="'.$home.'/small/img.php?i='.$item.''.($w ? '&amp;w='.$w : '').''.($h ? '&amp;h='.$h : '').'" /></a>';
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
}