<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_favorite
{
  private $attr;
  private $favorite;

  function __construct($params)
  {
    $this->attr = isset($params[0]) ? $params[0] : '';
    $this->favorite = isset($params[1]) ? $params[1] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`id` ' //'sortcolumn' => ' `ds_maindata_favorive`.`item` '
    );
  }

  public function name()
  {
    return array(
      'name' => '<i title="Избранное" class="icon-star-1"></i>', //А, че, оказывается можно и иконку засунуть
      'style' => 'max-width: 50px;min-width: 40px;',
      'nosort' => 1
    );
  }

  public function process()
  {
    if($this->favorite)
      $star = '<i title="Удалить лот из избранного" class="icon-star-clicked"></i>';
    else
      $star = '<i title="Добавить лот в избранное" class="icon-star-empty"></i>';

    return array(
      'col' => '<span class="icon_to_click" attr="'.$this->attr.'">'.$star.'</span>',
      'style' => 'text-align:center;'
    );
  }
}
