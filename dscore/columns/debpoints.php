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
      'name' => 'Баллы'
    );
  }

  public function process()
  {
    $price = $this->price;

    return array(
      'col' => $price,
      'style' => 'text-align:center;'
    );
  }
}

