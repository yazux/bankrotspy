<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_sample
{
  private $name;

  function __construct($params)
  {
    $this->name = isset($params[0]) ?  trim($params[0]) : '';
  }

  public function process()
  {
    return $this->name.' - test';
  }
}