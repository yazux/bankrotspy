<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_marketprice
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`market_price` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Рыночная цена, руб.'
    );
  }

  public function process()
  {
    $price = $this->price;

    if($price)
    {
      $price = strrev($price);
      $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
      $out_price = '';

      $i = 1;
      foreach($chars AS $val)
      {
        $out_price .= $val;
        if($i == 3)
        {
          $out_price .= ';psbn&'; //Неразрывный пробел наоборот
          $i = 0;
        }
        $i++;
      }
      $out_price = strrev($out_price);
    }
    else
      $out_price = '-';

    return array(
      'col' => $out_price,
      'style' => 'text-align:center;'
    );
  }
}

