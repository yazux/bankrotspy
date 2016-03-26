<?php

class defaultset {

    private static function get_types() {
        $types_def = array();
        $res = core::$db->query('SELECT * FROM `ds_maindata_type` ;');
        while($data = $res->fetch_array()) {
            $types_def[$data['id']] = 1;
        }
        return $types_def;
    }

    private static function get_places() {
        $places_def = array();
        $res = core::$db->query('SELECT * FROM  `ds_maindata_regions` ORDER BY `name` ASC;');
        while($data = $res->fetch_array()) {
            $places_def[$data['number']] = 1;
        }
        return $places_def;
    }

    private static function get_platforms() {
        $platforms_def = array();
        $res = core::$db->query('SELECT * FROM `ds_maindata_platforms` ORDER BY `platform_url` ASC;;');
        while($data = $res->fetch_array()) {
            $platforms_def[$data['id']] = 1;
        }
        return $platforms_def;
    }

    public static function get($params = array()) {
        $types_def = isset($params[0]) ?  $params[0] : self::get_types();
        $places_def = isset($params[1]) ? $params[1] : self::get_places();
        $platforms_def = isset($params[2]) ? $params[2] : self::get_platforms();

        //Настройки по умолчанию
        $set_table_array = array(
            'category' => -2,
            'page' => 1,
            'kmess' => 20,
            'svalue' => '',
            'types' => $types_def,
            'begin_date' => '',
            'end_date' => '',
            'altint' => '',
            'price_start' => '',
            'price_end' => '',
            'type_price' => 1,
            'sortcolumn' => '',
            'sorttype' => '',
            'places' => $places_def,
            'platforms' => $platforms_def,
            'status' => array(1=> 1, 2=>1, 3=> 0),
            'new_lots' => 0,
            'inn' => '',
            'au' => '',
            'case_number' => '',
            'trade_number' => '',
            'search_type' => '',
            'search_form_extend' => 0
        );

        return $set_table_array;
    }

}