<?
define('DS_ENGINE', 1);

//Основные функции
require_once('bazefunc.php');

//Очень урезанная версия ядра
new core();

//конфигурационный файл модуля, если есть
if(file_exists('modules/'.core::$module.'/--inc.php'))
  require_once('modules/'.core::$module.'/--inc.php');

//подключаем модуль-действие
require_once('versions/'.core::$apiversion.'/'.core::$module.'/'.core::$action.'.php');