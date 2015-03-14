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
    return array(
      'col' => mb_substr($this->name, 0, $this->lenght),
      'attr' => $this->attr,
      'class' => 'link_content'
    );
  }
}
