<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_profitproc
{
  private $price;
  private $platform_id;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
    $this->platform_id = isset($params[1]) ? $params[1] : '';
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

    $man_plf = func::get_manual_platforms();
    if(in_array($this->platform_id, $man_plf))
      $price = '<i onmouseover="toolTip(\'Не рассчитывается, т.к. цена определяется вручную\')" onmouseout="toolTip()" class="icon-help"></i>';

    return array(
      'col' => $price,
      'notcolored' => $not_colored,
      'style' => 'text-align:center;'
    );
  }
}

