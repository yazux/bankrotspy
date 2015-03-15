<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_beforedate
{
  private $time;
  private $endtime;

  function __construct($params)
  {
    $this->time = isset($params[0]) ? $params[0] : '';
    $this->endtime = isset($params[1]) ? $params[1] : '';
  }

  public function name()
  {
    return array(
      'name' => 'Дней до начала торгов'
    );
  }

  public function process()
  {
    $nowtime = strtotime(date('Y').'-'.date('n').'-'.date('j'));
    $start_time = strtotime(date('Y', $this->time).'-'.date('n', $this->time).'-'.date('j', $this->time));
    $days = (($start_time - $nowtime + 3600)/3600/24);
    if($days < 0)
    {
      //$days = ($start_time - $nowtime) / 3600 / 24;
      $end_time = strtotime(date('Y', $this->endtime).'-'.date('n', $this->endtime).'-'.date('j', $this->endtime));
      if($nowtime < $end_time)
        $days = 'торгуется';
      else
        $days = 'торги окончены';
    }

    return array(//
      'col' => $days,
      'style' => 'text-align:center;'
    );
  }
}

