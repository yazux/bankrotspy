<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_pricediff
{
  private $price;
  private $platform_id;
  private $type;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
    $this->platform_id = isset($params[1]) ? $params[1] : '';
    $this->type = isset($params[2]) ? $params[2] : '';
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

    $man_plf = func::get_manual_platforms();
    if(in_array($this->platform_id, $man_plf) AND $this->type == 2)
      $price = '<i onmouseover="toolTip(\'Не рассчитывается, т.к. цена определяется вручную\')" onmouseout="toolTip()" class="icon-help"></i>';

    return array(
      'col' => $price,
      'style' => 'text-align:center;'
    );
  }
}

