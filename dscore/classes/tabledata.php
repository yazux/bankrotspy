<?php


class tabledata
{
  private $columns = array();

  function __construct()
  {
    if($handle = opendir('dscore/columns'))
    {
      while (false !== ($entry = readdir($handle)))
      {
        if($entry != '.' AND $entry != '..' AND func::getextension($entry) == 'php')
          include_once('dscore/columns/' . ''.$entry );
      }
      closedir($handle);
    }
  }


  public function __call($name, $params)
  {
    $fname = $name;
    $name = 'column_'.$name;
    $class = new $name($params);
    $this->columns[$fname] = $class->name();
    return $class->process();
  }

  public function get_names()
  {
    return $this->columns;
  }

}