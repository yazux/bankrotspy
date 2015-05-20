<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_pricediff
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`price_dif` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Понижение цены, %',
      'addhtml' => '  onmouseover="toolTip(\'Разница между начальной и текущей ценой\')" onmouseout="toolTip()" '
    );
  }

  public function process()
  {
    $price = $this->price;

    $color_red = false;
    if($price < 0)
      $color_red = true;

    if($price === '')
      $price = '-';

    if($color_red)
      $price = '<span style="color:#ff7863">' .$price.'</span>';

    return array(
      'col' => $price,
      'style' => 'text-align:center;'
    );
  }
}

