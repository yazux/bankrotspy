<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_price
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ?  trim($params[0]) : '';
  }

  public function process()
  {
    $price = str_replace(' ', '', $this->price);
    $price = str_replace(',', '.', $price);
    $price = trim(str_replace('руб.', '', $price));
    $price = trim($price);

    if(mb_substr_count($price, '.') > 0)
    {
      $tprice = explode('.', $price);
      $price = $tprice[0];
    }

    return $price;
  }
}