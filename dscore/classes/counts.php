<?php
defined('DS_ENGINE') or die('web_demon laughs');

class counts
{
  private static $sql = '';
  private static $marks = array();
  private static $links = array();
  private static $out = array();
  private static $query_flag = 0;

  public static function cnew($mark, $sql, $link)
  {
    self::$sql .= $sql;
    self::$marks[] =  $mark;
    self::$links[$mark] = $link;
    return true;
  }

  public static function query()
  {
    if(self::$sql)
    {
      core::$db->multi_query(self::$sql);
      $i = 0;
      while(core::$db->more_results())
      {
        core::$db->next_result();
        $res = core::$db->store_result()->fetch_row();
        self::$out[self::$marks[$i]] = $res[0];
        $i++;
      }

      self::$query_flag = 1;
    }
    return true;
  }

  public static function all()
  {
    if(self::$marks)
    {
      if(!self::$query_flag)
        self::query();
    }

    if(self::$out)
      file_put_contents('data/cache/counters_cache.dat',serialize(self::$out));
    else
      self::$out = unserialize(file_get_contents('data/cache/counters_cache.dat'));

    return self::$out;
  }

  public static function link($mark)
  {
    if(isset(self::$links[$mark]))
      return self::$links[$mark];
  }

  public static function get($mark)
  {
    if(!self::$query_flag)
      self::query();

    if(isset(self::$out[$mark]))
      return self::$out[$mark];
    else
      return false;
  }
}