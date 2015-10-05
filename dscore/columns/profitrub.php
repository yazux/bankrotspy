<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_profitrub
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
            'sortcolumn' => ' `ds_maindata`.`profit_rub` '
        );
    }

    public function name()
    {
        return array(
            'name' => 'Прибыль, руб.',
            'addhtml' => '  onmouseover="toolTip(\'Разница между ценой на рынке и на торгах\')" onmouseout="toolTip()" '
        );
    }

    public function process()
    {
        $price = $this->price;
        $access = $this->access;
    
        $color_red = false;
        if($price < 0)
            $color_red = true;

        if($price && $access) {

            $price = strrev($price);
            $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
            $out_price = '';

            $i = 1;
            foreach($chars AS $val) {
                $out_price .= $val;
                if($i == 3) {
                    $out_price .= ';psbn&'; //Неразрывный пробел наоборот
                    $i = 0;
                }
                $i++;
            }
            $out_price = strrev($out_price);
        } elseif($access) {
            $out_price = '-';
        } else {
            $out_price = '<i class="fa fa-lock"></i>';
        }
    
        if($color_red)
            $out_price = '<span style="color:#ff7863">' .$out_price.'</span>';

        $man_plf = func::get_manual_platforms();
        if(in_array($this->platform_id, $man_plf) && $this->type == 2 && strlen($this->schedule) < 10) {
            if($access) {
                $out_price = '<i onmouseover="toolTip(\'Не рассчитывается, т.к. цена определяется вручную\')" onmouseout="toolTip()" class="icon-help"></i>';
            } else {
                $addition = ' onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')" onmouseout="toolTip()" ';
            }
        } elseif(!$access) {
            $addition = ' onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')" onmouseout="toolTip()" ';
        }
    
        return array(
            'col' => $out_price,
            'style' => 'text-align:center;',
            'addition' => $addition
        );
    }
}