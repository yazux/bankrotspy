<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_pricedif
{
  private $price;
  private $now_price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ?  trim($params[0]) : '';
    $this->now_price = isset($params[1]) ?  trim($params[1]) : '';
  }

  public function process()
  {
    return round(($this->price - $this->now_price)/$this->price*100);
  }
}