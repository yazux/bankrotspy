<?php
defined('DS_ENGINE') or die('web_demon laughs');

if( POST('submit') ) {
    $site_domain = text::st(POST('site_domain'));
    $site_name = text::st(POST('site_name'));
    $site_keywords = text::st(POST('site_keywords'));
    $site_description = text::st(POST('site_description'));
    $main_adv_text = text::st(POST('main_adv_text'));
    $private_attention = text::st(POST('private_attention'));

    $query = 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_domain).'" WHERE `key` = "site_name";';
    $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_name).'" WHERE `key` = "site_name_main";';
    $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_description).'" WHERE `key` = "description";';
    $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($site_keywords).'" WHERE `key` = "keywords";';
    $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($main_adv_text).'" WHERE `key` = "main_adv_text";';
    $query .= 'UPDATE `ds_settings` SET `val` = "'.core::$db->res($private_attention).'" WHERE `key` = "private_attention";';

    core::$db->multi_query($query);
    core::$db->multi_free();

    $art_text = POST('mess');
    core::$db->query('UPDATE `ds_reg_page` SET
            `text` = "'.core::$db->res($art_text).'",
            `cache` = "'.core::$db->res(text::presave($art_text)).'"
            WHERE `id`="1" LIMIT 1;');

    func::notify(lang('base_settings'), lang('settings_saved'), core::$home . '/control', lang('continue'));
}

$res = core::$db->query('SELECT * FROM  `ds_reg_page` WHERE `id` = "1";');
$data = $res->fetch_assoc();
$error = array();

engine_head(lang('base_settings'));
temp::HTMassign('site_domain', core::$set['site_name']);
temp::HTMassign('site_name', core::$set['site_name_main']);
temp::HTMassign('site_keywords', core::$set['keywords']);
temp::HTMassign('site_description', core::$set['description']);
temp::HTMassign('main_adv_text', core::$set['main_adv_text']);
temp::HTMassign('private_attention', core::$set['private_attention']);
temp::assign('text',$data['text']);
temp::display('control.index');
engine_fin();