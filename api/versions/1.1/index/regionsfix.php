<?php
defined('DS_ENGINE') or die('web_demon laughs');

$messages = array();

// Выбираем порцию лотов, у которых не определен регион
$lots = core::$db->query('SELECT * FROM `ds_maindata` WHERE place=0 LIMIT 0,100');
while( $lot = $lots->fetch_array() ) {
    if ( $lotSource = core::$db->query('SELECT * FROM `ds_maindata_source` WHERE lotid=' . $lot['id']) ) {
        $source = $lotSource->fetch_array();
        $fields = explode(';', $source['data']);
        $region = $fields[3];

        $res = core::$db->query('SELECT * FROM `ds_maindata_regions` WHERE `name`="' . core::$db->res($region) . '" OR `genitive`="' . core::$db->res($region) . '"');
        
        if ( $r = $res->fetch_assoc() ) { // Если регион найден
            $messages[] = 'Лоту ' . $lot['id'] . ' назначен регион #' . $r['id'] . ' - ' . $r['name'];
            core::$db->query('UPDATE `ds_maindata` SET `place`='.$r['id'].' WHERE `id`='.$lot['id']);
        } else { // Если регион не найден
            // Ищем среди уже добавленных регионов, которые привязаны к основным регионам
            $res = core::$db->query('SELECT * FROM `ds_maindata_regions_add` WHERE `name`="' . core::$db->res($region) .'"');
            if ( $additionRegion = $res->fetch_assoc() ) {
                if ( $additionRegion['region_id'] != 0 ) {
                    $messages[] = 'Лоту ' . $lot['id'] . ' назначен дополинтельный регион #' . $additionRegion['id'] . ' - ' . $additionRegion['name'];
                    core::$db->query('UPDATE `ds_maindata` SET `place`='.$additionRegion['region_id'].' WHERE `id`='.$lot['id']);
                } else {
                    $messages[] = 'У лота ' . $lot['id'] . ' определен дополнительный, но неназначенный регион #' . $additionRegion['id'] . ' - ' . $additionRegion['name'];
                }
            } else {
                $messages[] = 'Регион '.$region.' не найден и добавлен в дополнительные.';
                core::$db->query('INSERT INTO `ds_maindata_regions_add` (region_id, name) VALUES(0, "'.core::$db->res($region).'")');
            }
        }
        
    }
}

//engine_head(lang('admin_control'));
//temp::HTMassign('messages',$messages);
//temp::display('control.regionsfix');
//engine_fin();