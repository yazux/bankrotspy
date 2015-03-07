<?php
defined('DS_ENGINE') or die('web_demon laughs');

class nav
{
  public static $start;
  public static $page;
  public static $kmess;
  public static $rewrite;
  public static $postfix;
    
  function __construct($colp = 0)
  {
    if(!$colp)  
      self::$kmess = 10;
    else
      self::$kmess = $colp;
      
    self::$page = intval(abs(GET('page')));
    if(!self::$page)
      self::$page = 1;  
    self::$start = GET('page') ? self::$page * self::$kmess - self::$kmess : 0; 
  }

  static function display($all, $path, $text_back = '', $text_ret = '')
  {
    $out = '';
    $orpath = $path;
    if(self::$rewrite)
      $path = $path.self::$rewrite;
    else
      $path = $path.'page=';  
    $colpages = self::$kmess;  
    $pages=ceil($all/$colpages);  
    if ($pages > 1)
    {
      if(self::$page == 1)
      {
        if($pages > 2)  
          $out .= '<span class="navpgin">1</span>';  
        $out .= '<span class="navpgin">'.($text_back ? $text_back : lang('nav_back')).'</span>';  
      }
      else
      { 
        if($pages > 2) 
          $out .= '<a class="navpg" href="'.$orpath.self::$postfix.'">1</a>';
        $out .= '<a class="navpg" href="'.$path.(self::$page-1).self::$postfix.'">'.($text_back ? $text_back : lang('nav_back')).'</a>';
      }
      
      if(self::$rewrite)
      {
        // Форма если испольуется мод реврайт
        $out .= '
        <script language="JavaScript" type="text/javascript">
           function redirect(url) {
             document.location.href = url;
           }
        </script>';
        
        $out .= '<form method="get" style="display:inline" action="">'.temp::formid().'';
        $out .= '<select class="navselect" name="page" onchange="redirect(this.value)">';
        
        for($i=1;$i<=$pages;$i++)
        {
          $out .= '<option value="'.$orpath.self::$rewrite.$i.self::$postfix.'" '.($i == self::$page ? 'selected="selected"' : '' ).' >'.$i.'</option>';
        }
        $out .= '</select></form>'; 
      }
      else
      {
        // Форма для перехода без мод реврайта 
        $out .= '<form method="get" style="display:inline" action="">'.temp::formid().'';
        // режем на куски запрос, делаем соответствующие инпуты
        $q_pos=strpos($orpath, '?');
        $val_path=substr($orpath, $q_pos+1);
        $val_arr = explode('&amp;', $val_path);
        foreach($val_arr AS $key=>$value)
        {
          if($value)
          { 
            $val_pt = explode('=', $value);
            $out .= '<input type="hidden" name="'.$val_pt[0].'" value="'.$val_pt[1].'" />';
          }
        }
      
        $out .= '<select class="navselect" name="page" onchange="this.form.submit()">'; 
        for($i=1;$i<=$pages;$i++)
        {
          $out .= '<option value="'.$i.'" '.($i == self::$page ? 'selected="selected"' : '' ).' >'.$i.'</option>';  
        }  
        $out .= '</select></form>'; 
      }
      if(self::$page == $pages)
      {
         $out .= '<span class="navpgin">'.($text_ret ? $text_ret : lang('nav_to')).'</span>';
         if($pages > 2)
           $out .= '<span class="navpgin">'.$pages.'</span>'; 
      }
      else
      {  
        $out .= '<a class="navpg" href="'.$path.(self::$page+1).self::$postfix.'">'.($text_ret ? $text_ret : lang('nav_to')).'</a>';
        if($pages > 2)  
          $out .= '<a class="navpg" href="'.$path.$pages.self::$postfix.'">'.$pages.'</a>';
      }
      return $out; 
    }
    else
      return '';  
  }  
     
}
