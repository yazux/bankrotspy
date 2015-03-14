<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_platform
{
  private $pid;
  private $platforms = array();

  function __construct($params)
  {
    $this->pid = isset($params[0]) ? $params[0] : '';
    $this->url = isset($params[1]) ? $params[1] : '';

    $this->get_platforms();
  }

  public function name()
  {
    return 'Площадка';
  }

  public function process()
  {
    return array(
      'col' => '<a target="_blank" href="'.$this->url.'"><i class="icon-globe-table"></i>'.$this->platforms[$this->pid].'</a>',
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
