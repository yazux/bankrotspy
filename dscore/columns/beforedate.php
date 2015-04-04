<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_beforedate
{
  private $time;
  private $endtime;
  private $status;

  function __construct($params)
  {
    $this->time = isset($params[0]) ? $params[0] : '';
    $this->endtime = isset($params[1]) ? $params[1] : '';
    $this->status = isset($params[2]) ? $params[2] : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`start_time` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Дней до торгов'
    );
  }

  public function process()
  {
    $nowtime = strtotime(date('Y').'-'.date('n').'-'.date('j'));
    $start_time = strtotime(date('Y', $this->time).'-'.date('n', $this->time).'-'.date('j', $this->time));
    $days = (($start_time - $nowtime + 3600)/3600/24);
    if($days <= 0)
    {
      //$days = ($start_time - $nowtime) / 3600 / 24;
      $end_time = strtotime(date('Y', $this->endtime).'-'.date('n', $this->endtime).'-'.date('j', $this->endtime));
      if($nowtime <= $end_time)
      {
        //Определяем статус точнее
        if($this->status)
        {
          if($this->status == 'Оконченный')
            $days = 'Торги окончены';
          else
            $days = text::st($this->status);
        }
        else
          $days = 'Торгуется';

      }
      else
        $days = 'Торги окончены';
    }
    else
      $days = round($days,0);

    return array(
      'col' => $days,
      'style' => 'text-align:center;'
    );
  }
}

