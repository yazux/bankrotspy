<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_name
{
  private $name;
  private $lenght;
  private $attr;

  function __construct($params)
  {
    $this->name = isset($params[0]) ? $params[0] : '';
    $this->lenght = isset($params[1]) ? $params[1] : 0;
    $this->attr = isset($params[2]) ? $params[2] : '';
  }

  public function name()
  {
    return 'Лот';
  }

  public function process()
  {
    $name = $this->name;
    $now_lenght = mb_strlen($name);

    if($now_lenght > $this->lenght)
    {
      $name = mb_substr($this->name, 0, $this->lenght);
      $name = $name.'...';
    }

    return array(
      'col' => '<a target="_blank" class="namelink" href="'.core::$home.'/card/'.$this->attr.'">'.$name.'</a>'
    );
  }
}
