<?php


class tabledata
{
  private $columns = array();
  private $todata = array();
  private $sortcolumns = array();
  private $sortcolumn = '';
  private $sorttype = '';


  function __construct($sortcolumn, $sorttype)
  {
    if($handle = opendir('dscore/columns'))
    {
      while (false !== ($entry = readdir($handle)))
      {
        if($entry != '.' AND $entry != '..' AND func::getextension($entry) == 'php')
        {
          $classname = str_replace('.php', '', $entry);
          include_once('dscore/columns/' . '' . $entry);
          $fname = $classname;
          $classname = 'column_'.$classname;
          $class = new $classname(array());

          if(method_exists($class, 'before_load'))
          {
            $this->todata[$fname] = $class->before_load();
            $this->sortcolumns[$fname] = $this->todata[$fname]['sortcolumn'];
          }
        }
      }
      closedir($handle);

      if(isset($this->sortcolumns[$sortcolumn]) AND $sorttype > 0 AND $sorttype <= 2)
      {
        $this->sortcolumn = $sortcolumn;
        $this->sorttype = $sorttype;
      }
    }
  }


  public function __call($name, $params)
  {
    $fname = $name;
    $name = 'column_'.$name;
    $class = new $name($params);
    $arr_out = $class->name();
    $arr_out['classname'] = $fname;

    if(isset($this->sortcolumns[$fname]))
      $arr_out['sorttype'] = $this->sorttype;

    $this->columns[$fname] = $arr_out;
    return $class->process();
  }

  public function get_names()
  {
    return $this->columns;
  }

  public function get_sort_order()
  {
    $out_add_sql = '';
    if($this->sortcolumn)
      $out_add_sql = $this->sortcolumns[$this->sortcolumn];

    if($this->sorttype == 1)
      $out_add_sql = $out_add_sql.' ASC ';
    elseif($this->sorttype == 2)
      $out_add_sql = $out_add_sql.' DESC ';

    return $out_add_sql;
  }
}