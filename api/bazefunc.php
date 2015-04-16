<?php
defined('DS_ENGINE') or die('web_demon laughs');

spl_autoload_register('autoload');
function autoload($name)
{
  $rem = false;
  if(!$rem)
  {
    include_once('../dscore/classes/rem.php');
    $rem = true;
  }

  if(!rem::exists('load_class_'.$name))
  {
    $file = 'classes/' . $name . '.php';
    if(file_exists($file))
    {
      include_once($file);
      rem::remember('load_class_' . $name, 1);
    }
  }
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
  if(isset($_GET[$string]))
    return trim($_GET[$string]);
  return FALSE;
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