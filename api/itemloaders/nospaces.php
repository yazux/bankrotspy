<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_nospaces
{
  private $string;

  function __construct($params)
  {
    $this->string = isset($params[0]) ?  trim($params[0]) : '';
  }

  public function process()
  {
    $string = str_replace(' ', '', $this->string);

    return $string;
  }
}