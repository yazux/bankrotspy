<?php
define('DS_ENGINE', 1);

//Основные функции
require_once('dscore/bazefunc.php');

//Ядро
new core();

//Шаблонизатор
new temp('themes/'.core::$typetheme.'/'.core::$theme.'/templates/');

//конфигурационный файл модуля, если есть
if(file_exists('modules/'.core::$module.'/--inc.php'))
  require_once('modules/'.core::$module.'/--inc.php');

//подключаем модуль-действие
require_once('modules/'.core::$module.'/'.core::$action.'.php');

