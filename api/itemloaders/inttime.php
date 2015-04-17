<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_inttime
{
  private $time;

  function __construct($params)
  {
    $this->time = isset($params[0]) ?  trim($params[0]) : '';
  }

  public function process()
  {
    return strtotime($this->time);
  }
}