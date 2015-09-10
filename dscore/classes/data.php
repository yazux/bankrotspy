<?php
defined('DS_ENGINE') or die('web_demon laughs');

class data extends mysqli
{
  public static $request_count = 0;              //количество запросов к базе
  private static $last_sql;
  private static $file_error; 
    
  public function query($sql)
  {
    list(self::$file_error) = debug_backtrace();  
    self::$request_count++;
    self::$last_sql = $sql;
    if(mysqli::real_query(self::$last_sql))
      return new SQLResult($this);
    else
    {
      self::error();  
    }
  }   
  
  private function error($file_error=array())
  {
      ob_clean();
      $file_r = str_replace('\\','/',self::$file_error['file']);
      $file_r = str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_r);
      echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru"><body>';
      echo '<b>MySQLi ERROR in</b> "'.$file_r.'" <b>on line</b> '.self::$file_error['line'].'<hr/>';
      echo ''.mysqli_error($this).':<br/>'.self::$last_sql.'<br/></body></html>';
      exit(); 
  }
  
  public function res($string)
  {
    return mysqli::real_escape_string($string);  
  }
  
  public function req_count()
  {
    return self::$request_count;  
  }
  
  public function multi_query($sql)
  {
    list(self::$file_error) = debug_backtrace();
    self::$request_count++;
    self::$last_sql = $sql;
    $result = mysqli::multi_query(self::$last_sql);
    if ($result)
      return $result;
    else
    {
      self::error();
    }   
  }
  
  public function multi_free()
  {
     while(self::more_results())
     {
       self::next_result();
       self::store_result();  
     } 
  }
}

class SQLResult extends MySQLi_Result
{
  public function count()
  {
    $return = $this->fetch_row();
    return $return[0];
  }

  public function fetch()
  {
    return $this->fetch_assoc();
  }
  
    public function fetch_all($a)
    {
        $result = array();
        
        while ($row = $this->fetch_assoc()) {
            $result[] = $row;
        }
        return $result;
    }
}
