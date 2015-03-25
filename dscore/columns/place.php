<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_place
{
  private $type;
  private $places = array();

  function __construct($params)
  {
    $this->type = isset($params[0]) ? $params[0] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `regionname` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Регион'
    );
  }

  public function process()
  {
    $this->get_places();

    return array(
      'col' => $this->places[$this->type] ,
      'style' => 'text-align:center;'
    );
  }

  private function get_places()
  {
    if(!rem::exists('maintable_places'))
    {
      $res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ;');
      while($data = $res->fetch_array())
      {
        $this->places[$data['number']] = $data['name'];
      }
      rem::remember('maintable_places', serialize($this->places));
    }
    else
      $this->places = unserialize(rem::get('maintable_places'));
  }
}
