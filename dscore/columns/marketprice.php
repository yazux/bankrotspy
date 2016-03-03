<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_marketprice
{
    private $price;
    private $access;
    private $hint;
    private $getPrice = false;
    
    public function __construct($params)
    {
        $this->price = isset($params[0]) ? $params[0] : '';
        $this->access = !empty($params[1]) ? true : false;
        $this->hint = !empty($params[2]) ? $params[2] : false;
        $this->getPrice = !empty($params[3]) ? $params[3] : false;
    }

    public function before_load()
    {
        return array(
            'sortcolumn' => ' `ds_maindata`.`market_price` '
        );
    }

    public function name()
    {
        return array(
            'name' => 'Рыночная цена, руб.',
            'addhtml' => ' onmouseover="toolTip(\'Рыночная цена лота на рынке\')" onmouseout="toolTip()" '
        );
    }

    public function process()
    {
        $price = $this->price;
        $access = $this->access;
        $addition = '';
        
        if($price && $access) {
            $price = strrev($price);
            $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
            $out_price = '';

            $i = 1;
            // переделать на number_format
            foreach($chars AS $val) {
                $out_price .= $val;
                if($i == 3) {
                    $out_price .= ';psbn&'; //Неразрывный пробел наоборот
                    $i = 0;
                }
                $i++;
            }
            $out_price = strrev($out_price);
            
            if(!empty($this->hint)) {
                $addition = ' onmouseover="toolTip(\''.$this->hint.'\')" onmouseout="toolTip()" ';
            }
            
        } elseif(!$price && $access) { 
            if ($this->getPrice) {
                $out_price = '<a class="get_lot_price"><span>Узнать</span></a>';
            } else {
                $out_price = 'Узнать';
                $addition = ' onmouseover="toolTip(\'Информация доступна на тарифном плане VIP\')" onmouseout="toolTip()" ';
            }
        } else {
            $out_price = '<i class="fa fa-lock"></i>';
            $addition = ' onmouseover="toolTip(\'Информация доступна на тарифном плане VIP\')" onmouseout="toolTip()" ';
        }

        return array(
            'col' => $out_price,
            'style' => 'text-align:center;',
            'addition' => $addition
        );
    }
}