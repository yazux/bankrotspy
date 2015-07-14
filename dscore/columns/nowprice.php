<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_nowprice
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
      'sortcolumn' => ' `ds_maindata`.`now_price` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Текущая цена, руб.'
    );
  }

  public function process()
  {
    $price = $this->price;

    $price = strrev($price);
    $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
    $out_price = '';

    $i = 1;
    foreach($chars AS $val)
    {
      $out_price .= $val;
      if($i == 3)
      {
        $out_price .= ';psbn&'; //Неразрывный пробел наоборот
        $i = 0;
      }
      $i++;
    }
    $out_price = strrev($out_price);

    $man_plf = func::get_manual_platforms();
    if(in_array($this->platform_id, $man_plf) AND $this->type == 2)
      $out_price = 'Определяется вручную';

    return array(
      'col' => $out_price,
      'style' => 'text-align:center;'
    );
  }
}

