<?php

class loader_category
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