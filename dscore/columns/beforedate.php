<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_beforedate
{
  private $time;
  private $endtime;
  private $status;
  private $intstatus;

  function __construct($params)
  {
    $this->time = isset($params[0]) ? $params[0] : '';
    $this->endtime = isset($params[1]) ? $params[1] : '';
    $this->status = isset($params[2]) ? $params[2] : '';
    $this->intstatus = isset($params[3]) ? $params[3] : '';
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
      'name' => 'Статус'
    );
  }

  public function process()
  {
    $nowtime = strtotime(date('Y').'-'.date('n').'-'.date('j'));
    $start_time = strtotime(date('Y', $this->time).'-'.date('n', $this->time).'-'.date('j', $this->time));
    $days = (($start_time - $nowtime + 3600)/3600/24);
    if($days <= 0)
    {
      $end_time = strtotime(date('Y', $this->endtime).'-'.date('n', $this->endtime).'-'.date('j', $this->endtime));
      if($nowtime <= $end_time)
      {
        //Определяем статус точнее
        $before_close = array(3, 4, 5, 6);
        if(in_array($this->intstatus, $before_close))
        {
          //Если пришел один из статусов окончания
          $days = text::st($this->status);
        }
        else
          $days = 'Приём заявок';

        if($days == 'Оконченный')
          $days = 'Торги окончены';

      }
      else
        $days = 'Торги окончены';
    }
    else
      $days = round($days,0).' дня(-ей) до подачи заявок';

    return array(
      'col' => $days,
      'style' => 'text-align:center;'
    );
  }
}

