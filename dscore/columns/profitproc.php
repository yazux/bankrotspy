<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_profitproc
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
  }

  public function name()
  {
    return array(
      'name' => 'Доходность, %'
    );
  }

  public function process()
  {
    $price = $this->price;

    $color_red = false;
    if($price < 0)
      $color_red = true;

    if(!$price)
      $price = '-';

    if($color_red)
      $price = '<span style="color:#ff7863">' .$price.'</span>';

    return array(
      'col' => $price,
      'style' => 'text-align:center;'
    );
  }
}

