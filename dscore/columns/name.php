<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_name
{
  private $name;
  private $lenght;
  private $attr;
  private $item_arr;

  function __construct($params)
  {
    $this->name = isset($params[0]) ? $params[0] : '';
    $this->lenght = isset($params[1]) ? $params[1] : 0;
    $this->attr = isset($params[2]) ? $params[2] : '';
    $this->item_arr = isset($params[3]) ? $params[3] : array();
  }

  public function name()
  {
    return array(
      'name' => 'Лот',
      'style' => 'max-width: 200px;'
    );
  }

  private function replace_finded($text, $words_arr)
  {
    foreach($words_arr AS $val)
      $text = preg_replace('/'.preg_quote(text::st($val)).'/ui', '<span class="search_highlight">'.text::st($val).'</span>', $text);
    return $text;
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
    $name = text::st($name);

    if($this->item_arr)
      $name = $this->replace_finded($name, $this->item_arr);

    return array(
      'col' => '<a target="_blank" class="namelink" href="'.core::$home.'/card/'.$this->attr.'"><i class="icon-share"></i>'.$name.'</a>',
      'style' => 'max-width: 200px;'
    );
  }
}
