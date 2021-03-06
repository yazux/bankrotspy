<?php

if (php_sapi_name() == "cli") {
    foreach ($argv as $arg) {
        $arg = explode('=', $arg);
        if (count($arg) > 1) {
           $_GET[$arg[0]] = $arg[1]; 
        }
    }
}
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

define('DS_ENGINE', 1);

//Основные функции
require_once('dscore/bazefunc.php');
require_once('vendor/autoload.php');

//Ядро
new core();

//Шаблонизатор
new temp('themes/'.core::$typetheme.'/'.core::$theme.'/templates/');

//конфигурационный файл модуля, если есть
if(file_exists('modules/'.core::$module.'/--inc.php'))
  require_once('modules/'.core::$module.'/--inc.php');


if(!empty(core::$folder)) {
    //подключаем модуль-действие
    require_once('modules/'.core::$module.'/'.core::$folder.'/'.core::$action.'.php');
} else {
    require_once('modules/'.core::$module.'/'.core::$action.'.php');
}

