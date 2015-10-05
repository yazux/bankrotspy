<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_profitproc
{
    private $price;
    private $platform_id;
    private $type;
    private $access;
    private $schedule;
    
    function __construct($params)
    {
        $this->price = isset($params[0]) ? $params[0] : '';
        $this->platform_id = isset($params[1]) ? $params[1] : '';
        $this->type = isset($params[2]) ? $params[2] : '';
        $this->access = !empty($params[3]) ? true : false;
        $this->schedule = isset($params[4]) ? $params[4] : false;
    }

    public function before_load()
    {
        return array(
            'sortcolumn' => ' `ds_maindata`.`profit_proc` '
        );
    }

    public function name()
    {
        return array(
            'name' => 'Доходность, %',
            'addhtml' => '  onmouseover="toolTip(\'Вероятная доходность операции по приобретению лота на торгах и продаже его на рынке\')" onmouseout="toolTip()" '
        );
    }

    public function process()
    {
        $price = $this->price;
        $access = $this->access;
    
        $color_red = false;
    
        if($price < 0)
            $color_red = true;

        if(!$price && $access) {
            $price = '-';
        } elseif(!$access) {
            $price = '<i class="fa fa-lock"></i>';
        }
  
        $not_colored = $price;

        if($color_red && $access)
            $price = '<span style="color:#ff7863">' .$price.'</span>';

        $man_plf = func::get_manual_platforms();
    
        if (in_array($this->platform_id, $man_plf) && $this->type == 2 && strlen($this->schedule) < 10) {
            if($access) {
                $price = '<i onmouseover="toolTip(\'Не рассчитывается, т.к. цена определяется вручную\')" onmouseout="toolTip()" class="icon-help"></i>';
                $not_colored = $price;
            } else {
                $addition = 'onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')" onmouseout="toolTip()"';
                $not_colored = $price;
            }
        } elseif(!$access) {
            $addition = 'onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')" onmouseout="toolTip()"';
            $not_colored = $price;
        }
    
        return array(
            'col' => $price,
            'notcolored' => $not_colored,
            'style' => 'text-align:center;',
            'addition' => $addition
        );
    }
}