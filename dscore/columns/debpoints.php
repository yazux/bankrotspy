<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_debpoints
{
  private $price;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`debpoints` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Баллы',
      'addhtml' => ' onmouseover="toolTip(\'Рейтинг ликвидности лота, рассчитанный на основе 3-х критериев:<br/>3 - высокое качество лота<br/>2 - среднее качество лота<br/>0-1 - низкое качество лота\')" onmouseout="toolTip()" '
    );
  }

  public function process()
  {
    $price = $this->price;
    if(!$price)
      $price = '<i onmouseover="toolTip(\'У этого лота пока нет баллов\')" onmouseout="toolTip()" class="icon-help"></i>';

    return array(
      'col' => $price,
      'style' => 'text-align:center;'
    );
  }
}

