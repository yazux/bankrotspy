<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_nowprice {
    private $price;
    private $platform_id;
    private $type;
    private $schedule;
    private $calcNTime;

    function __construct($params) {
        $this->price = isset($params[0]) ? $params[0] : '';
        $this->platform_id = isset($params[1]) ? $params[1] : '';
        $this->type = isset($params[2]) ? $params[2] : '';
        $this->schedule = isset($params[3]) ? $params[3] : false;
        $this->calcNTime = isset($params[4]) ? $params[4] : false;
    }

    public function before_load() {
        return array(
            'sortcolumn' => ' `ds_maindata`.`now_price` '
        );
    }

    public function name() {
        return array(
            'name' => 'Текущая цена, руб.'
        );
    }

    public function process() {
        
        $price = $this->price;
        if ( $price < 1 ) {
            $price = 1;
        }

        $price = strrev($price);
        $chars = preg_split('//', $price, -1, PREG_SPLIT_NO_EMPTY);
        $out_price = '';

        $i = 1;
        foreach ($chars as $val) {
            $out_price .= $val;
            if ($i == 3) {
                $out_price .= ';psbn&'; //Неразрывный пробел наоборот
                $i = 0;
            }
            $i++;
        }
        
        // Цена, которая будет показываться в колонке
        $out_price = strrev($out_price);
        // Стиль отображения цены
        $style = 'text-align: center;';
        $isCalculated = 0;
        
        $man_plf = func::get_manual_platforms();
        
        if(in_array($this->platform_id, $man_plf) && $this->type == 2 && strlen($this->schedule) < 10) {
            $out_price = "Уточните цену на площадке";
            //$out_price = "<span onmouseout=\"toolTip()\" onmouseover=\"toolTip('Уточните цену на площадке')\">Определяется вручную</span>";
            $style .= ' color: #d27600;';
            $isCalculated = 1;
        } elseif( ($this->type == 2) && ($this->calcNTime > 0) ) {
            $out_price = "<span onmouseout=\"toolTip()\" onmouseover=\"toolTip('Расчетная цена, уточните на площадке')\">" . $out_price . "</span>";
            $style .= ' color: #d27600;';
            $isCalculated = 1;
        }

        return array(
            'isCalculated' => $isCalculated,
            'col' => $out_price,
            'style' => $style
        );
    }
}