<?
define('DS_ENGINE', 1);

//Основные функции
require_once('bazefunc.php');

//Очень урезанная версия ядра
class core
{
  public static $apiversion = '1.0';       //Загружаемый модуль
  public static $module = 'index';       //Загружаемый модуль
  public static $action = 'index';       //Действие модуля
  public static $db;                     //MySQLi
  public static $home;

  function __construct()
  {
    self::system_set();
    self::data_connect();
    self::default_set();
    self::initiate_module();
  }

  private static function system_set()
  {
    //Error_Reporting(E_ALL & ~E_NOTICE);
    @ini_set('session.use_trans_sid', '0');
    @ini_set('arg_separator.output', '&amp;');
    mb_internal_encoding('UTF-8');
    date_default_timezone_set('Europe/London');

    if (get_magic_quotes_gpc())
      self::del_mag_quotes();

    ob_start();
  }


  private static function del_mag_quotes()
  {
    //Взято из johnCMS
    // Удаляем слэши, если открыт magic_quotes_gpc
    $in = array(& $_GET, & $_POST, & $_COOKIE);
    while (list($k, $v) = each($in)) {
      foreach ($v as $key => $val) {
        if (!is_array($val)) {
          $in[$k][$key] = stripslashes($val);
          continue;
        }
        $in[] = & $in[$k][$key];
      }
    }
    unset ($in);
    if (!empty ($_FILES)) {
      foreach ($_FILES as $k => $v) {
        $_FILES[$k]['name'] = stripslashes((string) $v['name']);
      }
    }
  }

  private  static function data_connect()
  {
    require_once('../dscore/includes/db.php');
    require_once('../dscore/classes/data.php');
    self::$db = new data($db_host,$db_user,$db_pass,$db_name);
    self::$db->query('set names utf8');
    //self::$db->query('set sql_big_selects=1');  //Потом убрать
  }

  private static function default_set()
  {
    self::$home = 'http://'.$_SERVER['HTTP_HOST'];
  }

  private static function initiate_module()
  {
    self::$apiversion = isset($_GET['apiversion']) ? $_GET['apiversion'] : self::$apiversion;
    self::$module = isset($_GET['core_mod']) ? $_GET['core_mod'] : self::$module;
    self::$action = isset($_GET['core_act']) ? $_GET['core_act'] : self::$action;

    if(preg_match('/[^0-9\.]+/',self::$apiversion))
      self::error('Wrong api version');

    if(!preg_match('/[^0-9a-z\_]+/',self::$module) and !preg_match('/[^0-9a-z\_]+/',self::$action))
    {
      if(!file_exists('versions/'.core::$apiversion.'/'.self::$module.'/'.self::$action.'.php'))
        self::error('Module not exists');
    }
    else
      self::error('Wrong module name');

  }

  function error($error='')
  {
    if($error)
      die($error);
    else
      die('Unknown api error');
  }
}

new core();

//конфигурационный файл модуля, если есть
if(file_exists('modules/'.core::$module.'/--inc.php'))
  require_once('modules/'.core::$module.'/--inc.php');

//подключаем модуль-действие
require_once('versions/'.core::$apiversion.'/'.core::$module.'/'.core::$action.'.php');