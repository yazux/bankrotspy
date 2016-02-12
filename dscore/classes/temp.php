<?php
defined('DS_ENGINE') or die('web_demon laughs');

class temp
{
  private static $vars = array();
  private static $vars_global = array();  
  private static $temp_path;
  private static $file_error;
  private static $file;
  private static $file_eval;
  
  function __construct($path)
  {  
    self::$temp_path = $path;
  }
    
  public static function assign($name, $value = '')
  {
    //Безопасный вывод
    if (!is_array($value))  
      self::$vars[$name] = htmlentities($value, ENT_QUOTES, 'UTF-8');
    else
    {
      list(self::$file_error) = debug_backtrace();
      self::error('Could\'nt use temp::assign for arrays, please use temp::HTMassign');  
    }
  }  
  
  public static function HTMassign($name, $value = '')
  {
    //Вывод HTML  
    self::$vars[$name] = $value;
  }
  
  public static function URLassign($name, $value = '')
  {
    //Вывод ссылок
    if (!is_array($value))  
      self::$vars[$name] = rawurlencode($value);
    else
    {
      list(self::$file_error) = debug_backtrace();
      self::error('Could\'nt use temp::URLassign for arrays, please use temp::HTMassign');  
    }
  }
  
  public static function GLBassign($name, $value = '')
  {
    //Переменные доступны для всех шаблонов, перезаписываються остальными методами
    if (!is_array($value))  
      self::$vars_global[$name] = htmlentities($value, ENT_QUOTES, 'UTF-8');
    else
    {
      list(self::$file_error) = debug_backtrace();
      self::error('Could\'nt use temp::GLBassign for arrays, please use temp::HTMassign');  
    }
  }
  
  private static function error($error,$file_error=array())
  {
      ob_clean();
      $file_r = str_replace('\\','/',self::$file_error['file']);
      $file_r = str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_r);                      
      echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru"><body>';
      echo '<b>Template ERROR in</b> "'.$file_r.'" <b>on line</b> '.self::$file_error['line'].'<hr/>';
      echo ''.$error.'<br/></body></html>';
      exit(); 
  }
  
  private static function file_inc($m)
  {
     $file = $m[1];
     if(rem::exists($file))
       $file_cont = rem::get($file);  
     else
     {
       if (file_exists(self::$temp_path.$file))
       {
         $file_cont = file_get_contents(self::$temp_path.$file);
         rem::remember($file, $file_cont);  
       }
       else
         self::error('File '.$file.' not found in '.self::$temp_path);  
     }  
     return $file_cont;
  }

  private static function del_short_tags($m)
  {
    if($m[1])
      return '<?php echo '.$m[2].'?>';
    else
      return '<?php '.$m[2].'?>';
  }

  public static function display($file)
  {
    list(self::$file_error) = debug_backtrace();
    self::$file = $file;
    unset($file);
    
    if (!self::$file)
      self::error('Template is not defined');
    self::$file = self::$file.'.tpl';
    if(!file_exists(self::$temp_path.self::$file))
      self::error('File '.self::$file.' not found in '.self::$temp_path);  
    
    
    foreach (self::$vars_global as $key => $value)
      $$key = $value;
   
    foreach (self::$vars as $key => $value)
      $$key = $value;
      
    Error_Reporting(E_ALL & ~E_NOTICE);
                                            /* <? temp::include('file.tpl') ?>   */ 
    self::$file_eval = file_get_contents(self::$temp_path.self::$file);
    self::$file_eval =  preg_replace_callback('#\<\? temp\:\:include\(\'(.*?)\'\) \?\>#si', array('self', 'file_inc'),self::$file_eval);
    self::$file_eval = preg_replace_callback('/\<\?(\=)?(.*?)\?\>/si', array('self', 'del_short_tags'), self::$file_eval);

    eval('?>'.self::$file_eval.'<?');
    
    Error_Reporting(E_ALL & E_NOTICE);
    self::$vars = array();
  }  
  
  public static function formid()
  { 
    echo '<input type="hidden" name="formid" value="'.core::$formid.'" />';  
  }  
}   
