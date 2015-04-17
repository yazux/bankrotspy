<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_type
{
  private $type;

  function __construct($params)
  {
    $this->type = isset($params[0]) ?  trim($params[0]) : '';
  }

  public function process()
  {
    if(preg_match('/' . preg_quote('предложение') . '/ui', $this->type))
      $type = 2;
    elseif(preg_match('/' . preg_quote('аукцион') . '/ui', $this->type))
      $type = 1;
    else
      $type = '';

    return $type;
  }
}