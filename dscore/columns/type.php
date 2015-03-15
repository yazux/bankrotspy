<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_type
{
  private $type;
  private $types = array();

  function __construct($params)
  {
    $this->type = isset($params[0]) ? $params[0] : '';

    $this->get_types();
  }

  public function name()
  {
    return array(
      'name' => 'Тип'
    );
  }

  public function process()
  {
    return array(
      'col' => $this->types[$this->type],
      'style' => 'text-align:center;'
    );
  }

  private function get_types()
  {
    if(!rem::exists('maintable_types'))
    {
      $res = core::$db->query('SELECT * FROM  `ds_maindata_type` ;');
      while($data = $res->fetch_array())
      {
        $this->types[$data['id']] = $data['type_name'];
      }
      rem::remember('maintable_types', serialize($this->types));
    }
    else
      $this->types = unserialize(rem::get('maintable_types'));
  }
}
