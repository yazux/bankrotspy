<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_begindate
{
  private $time;

  function __construct($params)
  {
    $this->time = isset($params[0]) ? $params[0] : '';
  }

  public function name()
  {
    return array(
      'name' => 'Дата начала'
    );
  }

  public function process()
  {
    return array(//
      'col' => date('j',$this->time) . ' ' . ds_time($this->time, '%B2 %Y, %H:%M'),
      'style' => 'text-align:center;'
    );
  }
}
