<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_place
{
  private $place;

  function __construct($params)
  {
    $this->place = isset($params[0]) ?  trim($params[0]) : '';
  }

  private function get_regions()
  {
    $allplaces = array();
    $res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ;');
    while($data = $res->fetch_array())
    {
      $allplaces[$data['number']] = $data['genitive'];
    }
    return $allplaces;
  }

  private function replace_not_valid($data)
  {
    //В этот массив добавлять пару старое значение - новое значение
    $replace_arr = array(
      'Московсской' => 'Московской'
    );

    $new_replace_arr = array();
    foreach ($replace_arr AS $key=>$value)
    {
      $new_replace_arr[mb_strtolower(trim($key))] = mb_strtolower(trim($value));
    }

    return strtr(mb_strtolower($data), $new_replace_arr);
  }

  public function process()
  {
    $place = '';
    $allplaces = $this->get_regions();
    $this->place = $this->replace_not_valid($this->place);

    //Первая попытка определения
    //С регистронезависимым поиском по точному совпадению
    foreach($allplaces AS $key=>$value)
    {
      if($this->place)
      {
        if(preg_match('/' . preg_quote($value) . '/ui', $this->place))
        {
          $place = $key;
          break;
        }
      }
    }

    return $place;
  }
}