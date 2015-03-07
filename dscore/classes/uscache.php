<?php
defined('DS_ENGINE') or die('web_demon laughs');
  
class uscache
{
  public static function rem($key, $value)
  {
    //запоминаем переменную  
    file_put_contents(self::get_cache_file($key), $value, LOCK_EX);
    return TRUE;  
  }
  
  public static function get($key)
  {
    //Выдаем переменную
    if(file_exists(self::get_cache_file($key)))
      return file_get_contents(self::get_cache_file($key));
    else
      return FALSE;  
  }
  
  public static function ex($key)
  {
    //проверяем существование переменной  
    if(file_exists(self::get_cache_file($key)))
      return TRUE;
    else
      return FALSE;  
  }
  
  public static function del($key)
  {
    //Удаляем переменную
    if(file_exists(self::get_cache_file($key)))
    {
      unlink(self::get_cache_file($key));
      return TRUE;
    }
    else
      return FALSE;  
  }
  
  private static function get_cache_file($key)
  {
    return ('data/usercache/'.core::$user_id.'.'.$key.'.dat');
  }
}