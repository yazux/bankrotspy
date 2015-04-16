<?php
defined('DS_ENGINE') or die('web_demon laughs');

class bcdata
{

  public function __call($name, $params)
  {
    include_once('itemloaders/'.$name.'.php');
    $classname = 'loader_'.$name;
    $class = new $classname($params);
    return $class->process();
  }

}