<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_begindate
{
  private $time;

  function __construct($params)
  {
    $this->time = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`start_time` '
    );
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
      'col' => date('d',$this->time) . '.' . ds_time($this->time, '%m.%Y, %H:%M'),
      'style' => 'text-align:center;font-size: 12px;'
    );
  }
}

