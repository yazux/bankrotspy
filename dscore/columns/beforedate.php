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
    $nowtime = time();//strtotime(date('Y').'-'.date('n').'-'.date('j'));
    $start_time =$this->time;// strtotime(date('Y', $this->time).'-'.date('n', $this->time).'-'.date('j', $this->time));
    $days = (($start_time - $nowtime + 3600)/3600/24);
 
    $before_close = array(3, 4, 5, 6);
    
    if (in_array($this->intstatus, $before_close)) {
        //Если пришел один из статусов окончания
        $days = text::st($this->status);
    } elseif ($days <= 0) {
        $end_time = $this->endtime; //strtotime(date('Y', $this->endtime).'-'.date('n', $this->endtime).'-'.date('j', $this->endtime));
       //var_dump($nowtime);
       //var_dump($end_time);
       //var_dump(date('H:i:s'));
        if ($nowtime <= $end_time) {
            if ($this->status == 'Оконченный') {
                $days = 'Торги окончены';
            } else {
                $days = 'Приём заявок';
            }
        } else {
            $days = 'Торги окончены';
        }
    } else {
        $days = round($days,0).' '.func::get_num_ending(round($days,0), array('день', 'дня', 'дней')).' до подачи заявок';
    }


    return array(
      'col' => $days,
      'style' => 'text-align:center;'
    );
  }
}

