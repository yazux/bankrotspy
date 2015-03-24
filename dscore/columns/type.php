<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_type
{
  private $type;
  private $types = array();

  function __construct($params)
  {
    $this->type = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`type` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Тип'
    );
  }

  public function process()
  {
    $this->get_types();

    $custom_names = array(
      1 => 'ОА',
      2 => 'ПП'
    );


    return array(
      'col' => (isset($custom_names[$this->type]) ? $custom_names[$this->type] : $this->types[$this->type]) ,
      'style' => 'text-align:center;',
      'addition' => 'onmouseover="toolTip(\''.$this->types[$this->type].'\')" onmouseout="toolTip()"'
    );
  }

  private function get_types()
  {
    if(!rem::exists('maintable_types'))
    {
      $res = core::$db->query('SELECT * FROM  `ds_maindata_type` ;');
      while($data = $res->fetch_array())
      {
        $this->types[$data['id']] = text::st($data['type_name']);
      }
      rem::remember('maintable_types', serialize($this->types));
    }
    else
      $this->types = unserialize(rem::get('maintable_types'));
  }
}
