<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_debpoints
{
  private $price;
  private $debnotice;

  function __construct($params)
  {
    $this->price = isset($params[0]) ? $params[0] : '';
    $this->debnotice = isset($params[1]) ? $params[1] : '';
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
    if($price == -1)
    {
      $price = '<i onmouseover="toolTip(\'У этого лота пока нет баллов\')" onmouseout="toolTip()" class="icon-help"></i>';
      $class = '';
      $addition = '';
    }
    else
    {
      if($this->debnotice)
      {
        $class = 'cell_with_notify';
        $addition = ' onmouseover="toolTip(\''.text::st(str_replace("\n", '<br/>', str_replace("\r\n", "\n", $this->debnotice))).'\')" onmouseout="toolTip()" ';
      }
    }

    return array(
      'col' => $price,
      'style' => 'text-align:center;padding: 0px;',
      'customclass' => $class,
      'addition' => $addition
    );
  }
}

