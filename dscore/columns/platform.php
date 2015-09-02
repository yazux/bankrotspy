<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_platform
{
  private $pid;
  private $platforms = array();
  private $fedlink;

  function __construct($params)
  {
    $this->pid = isset($params[0]) ? $params[0] : '';
    $this->url = isset($params[1]) ? $params[1] : '';
    $this->fedlink = isset($params[2]) ? $params[2] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`platform_id` ' //'sortcolumn' => ' `ds_maindata_platforms`.`platform_url` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Площадка, Федресурс',
      'nosort' => 1
    );
  }

  public function process()
  {
    $this->get_platforms();

    $addout = '';
    if($this->fedlink)
      $addout = ', <a style="color:green;white-space:nowrap;" target="_blank" href="'.$this->fedlink.'"><i class="icon-globe-table"></i>fedresurs.ru</a>';

    return array(
      'col' => '<a style="color:green;white-space:nowrap;" target="_blank" href="'.$this->url.'"><i class="icon-globe-table"></i>'.$this->platforms[$this->pid].'</a>'.$addout,
      'style' => 'text-align:center;'
    );
  }

  private function get_platforms()
  {
    if(!rem::exists('maintable_platforms'))
    {
      $res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ;');
      while($data = $res->fetch_array())
      {
        $this->platforms[$data['id']] = $data['platform_url'];
      }
      rem::remember('maintable_platforms', serialize($this->platforms));
    }
    else
      $this->platforms = unserialize(rem::get('maintable_platforms'));
  }
}
