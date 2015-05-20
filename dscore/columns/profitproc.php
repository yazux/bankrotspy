<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_profitproc
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`profit_proc` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Доходность, %',
      'addhtml' => '  onmouseover="toolTip(\'Вероятная доходность операции по приобретению лота на торгах и продаже его на рынке\')" onmouseout="toolTip()" '
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

    $not_colored = $price;

    if($color_red)
      $price = '<span style="color:#ff7863">' .$price.'</span>';

    return array(
      'col' => $price,
      'notcolored' => $not_colored,
      'style' => 'text-align:center;'
    );
  }
}

