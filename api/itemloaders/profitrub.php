<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_profitrub
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
    $this->price = abs(intval($this->price));
    $this->now_price = abs(intval($this->now_price));

    if($this->price)
      return round(($this->price - $this->now_price), 0);
    else
      return 0;
  }
}