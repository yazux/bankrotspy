<?php
defined('DS_ENGINE') or die('web_demon laughs');

//Класс для общения пользовательских функций
class rem
{
  private static $vars = array();

  public static function remember($key, $value)
  {
    //запоминаем переменную
    self::$vars[$key] = $value;
    return TRUE;
  }

  public static function get($key)
  {
    //Выдаем переменную
    if(!empty(self::$vars[$key]))
      return self::$vars[$key];
    return FALSE;
  }

  public static function exists($key)
  {
    //проверяем существование переменной
    return (!empty(self::$vars[$key]));
  }
}