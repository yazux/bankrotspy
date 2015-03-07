<?php
  
class mail_temp
{
  private static $vars = array();
  private static $temp_path;
  private static $file_error;
  private static $file;
  private static $content;
  
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
  
  private function error($error,$file_error=array())
  {
      ob_clean();
      $file_r = str_replace('\\','/',self::$file_error['file']);
      $file_r = str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_r);                      
      echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru"><body>';
      echo '<b>Template ERROR in</b> "'.$file_r.'" <b>on line</b> '.self::$file_error['line'].'<hr/>';
      echo ''.$error.'<br/></body></html>';
      exit(); 
  }
  
  public static function get($file)
  {
    list(self::$file_error) = debug_backtrace();
    self::$file = $file;
    unset($file);
    
    if (!self::$file)
      self::error('Template is not defined');
    self::$file = self::$file.'.tpl';
    if(!file_exists(self::$temp_path.self::$file))
      self::error('File '.self::$file.' not found in '.self::$temp_path);  
    
    self::$content = file_get_contents(self::$temp_path.self::$file);
    
    
    foreach (self::$vars as $key => $value)
    {
       self::$content = str_replace('{$'.$key.'}', $value, self::$content); 
    }
      
    self::$vars = array();
    return self::$content;  
  }    
}