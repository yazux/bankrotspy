<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(core::$rights < 100)
  denied();


//исходне права для групп
$defaultRights = array (
    'common' => array(
        'edit_user' => 0,
        'del_user' => 0,
        'edit_smiles' => 0,
        'stats_create' => 0,
        'stats_edit' => 0,
        'stats_delete' => 0,
        'comm_edit' => 0,
        'comm_delete' => 0,
        'ban_users' => 0,
        'comm_self_edit' => 1,
        'comm_self_delete' => 1,
        'create_comm' => 1,
        'tech_support' => 0,
        'news_create' => 0,
        'news_edit' => 0,
        'news_delete' => 0,
    ),
    // платный контент, управление доступом в админке у групп пользователей
    'paid' => array(
        'export_favorites'      => 0, // экспорт избранных лотов
        'add_favorites'         => 0, // добавление в избранные лоты
        'rating_arbitration'    => 0, // рейтинг АУ
        'scores_debtor'         => 0, // Баллы в деб. задолжности
        'histogram_goods'       => 0, // Гистограмма аналогичных товаров
        'cost_meter'            => 0, // Стоимость М2
        'planner_lots'          => 0, // Планировщик избранных лотов
        'document_creation'     => 0, // Заявка на участие в торгах
        'get_lot_price'         => 0, // Поиск цены для лотов без РЦ
        'lot_note'              => 0, // комментарии к избранным лотам
    )
);