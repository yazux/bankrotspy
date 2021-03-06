<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_number
{
  private $number;
  private $attr;

  function __construct($params)
  {
    $this->number = isset($params[0]) ? $params[0] : '';
    $this->attr = isset($params[1]) ? $params[1] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`code` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Номер лота'
    );
  }

  public function process()
  {
    return array(
      'col' => '<a target="_blank" class="namelink" href="'.core::$home.'/card/'.$this->attr.'"><i class="icon-hammer"></i>'.$this->number.'</a>',
      'style' => 'text-align:center;white-space:nowrap;'
    );
  }
}

