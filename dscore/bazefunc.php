<?php
defined('DS_ENGINE') or die('web_demon laughs');

spl_autoload_register('autoload');
function autoload($name) {
    $rem = false;
    if(!$rem) {
        include_once('dscore/classes/rem.php');
        $rem = true;
    }

    if(!rem::exists('load_class_'.$name)) {
        $file = 'dscore/classes/' . $name . '.php';
        if(file_exists($file)) {
            include_once($file);
            rem::remember('load_class_' . $name, 1);
        }
    }
}

rem::remember('microtime', microtime(1));

function lang($string='')
{
  if(!empty(core::$ln[$string]))
    return core::$ln[$string];
  else
    return '[lang::'.$string.']';
}

function engine_head($title = '', $keywords = '', $description = '')
{
  if(!$title)
    $title = htmlentities(core::$set['site_name_main'], ENT_QUOTES, 'UTF-8');
  core::$page_title = $title.' - '.htmlentities(core::$set['site_name'], ENT_QUOTES, 'UTF-8');
  
    if(!empty($keywords)) {
        core::$page_keywords = htmlentities($keywords, ENT_QUOTES, 'UTF-8');
    }
    
    if(!empty($description)) {
        core::$page_description = htmlentities($description, ENT_QUOTES, 'UTF-8');
    }
  
  unset($title);
  require_once('dscore/head.php');
}

function engine_fin()
{
  require_once('dscore/fin.php');
  exit();
}

function denied()
{
  list($file_error) = debug_backtrace();
  $file_r = str_replace('\\','/',$file_error['file']);
  $file_r = str_replace($_SERVER['DOCUMENT_ROOT'],'',$file_r);
  echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru"><body>';
  echo '<b>ACCESS ERROR!</b> Denied() called in "'.$file_r.'" <b>on line</b> '.$file_error['line'].'</body></html>';
  exit();

  //header('Location: ' . core::$home);
  exit();
}

function GET($string)
{
    if(isset($_GET[$string])) {
        $param = trim($_GET[$string]);
        return $param;
    }
    return false;
}

function smart_trim($string)
{
  $string = str_replace("\r\n", "\n", $string);
  $first_pos = mb_strpos($string, "\n");
  if($first_pos === false)
    return trim($string); //Если в строке нет переносов, тримим обычной функцией
  else
  { //Ну а если есть, то удаляем в начале ТОЛЬКО пустые строки, проблелы в начале строки не трогаем
    while(trim(mb_substr($string, 0, ($first_pos+1))) == '')
    {
      $string = mb_substr($string, ($first_pos+1));
      $first_pos = mb_strpos($string, "\n");
    }
    return rtrim($string); //В конце строки удаляем все
  }
}

function POST($string)
{
  if(isset($_POST[$string]))
  {
    if (is_array($_POST[$string]))
      return $_POST[$string];
    return smart_trim($_POST[$string]);
  }
  return FALSE;
}

function CAN($what, $editing_rights)
{
  //Функция прав
  if(core::$user_id)
  {
    $editing_rights = intval(abs($editing_rights));
    $rights_arr = core::$all_rights;

    //Определяем может ли юзер редачить $what
    if(core::$rights < $editing_rights)
      return FALSE;
    else
    {
      if(!empty($rights_arr[core::$rights][$what]))
        return TRUE;
      else
        return FALSE;
    }
  }
  return FALSE;
}

function ds_time($timestamp, $format = '')
{
  if(!$format)
    $format = '%d %B2 %Y, %H:%M';

  if (strpos($format, '%B2') === FALSE)
    return strftime($format, $timestamp);
  $month_number = date('n', $timestamp);
  $rusformat = str_replace('%B2', lang('month_r_'.$month_number), $format);
  return strftime($rusformat, $timestamp);
}

function ajax_response($data) {
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}