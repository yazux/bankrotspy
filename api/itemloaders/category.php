<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loader_category
{
  private $name;
  private $descr;

  function __construct($params)
  {
    $this->name = isset($params[0]) ?  trim($params[0]) : '';
    $this->descr = isset($params[1]) ?  trim($params[1]) : '';
  }

  private function merge_name($data_name, $description)
  {
    if(!$data_name)
      $name = $description;
    elseif($data_name != $description)
    {
      if(mb_substr_count($description, $data_name))
        $name = $description;
      else
        $name = trim($data_name) . '. ' . $description;
    }
    else
      $name = $data_name;

    return $name;
  }

  private function get_keys_category()
  {
    $outarr = array();
    $res = core::$db->query('SELECT * FROM `ds_main_cat_spec` ;');
    while($data = $res->fetch_array())
    {
      $data['slova'] = mb_strtolower(str_replace('*', '', $data['slova']));
      $outarr[$data['id']] = explode("\n", str_replace("\r\n", "\n", $data['slova']));
    }
    return $outarr;
  }

  public function process()
  {
    //Сшиваем название и описание для лучшего поиска
    $resname = mb_strtolower($this->merge_name($this->name, $this->descr));

    //Получаем список ключевых слов
    $catarr = $this->get_keys_category();

    //удаляем некоторые символы
    $replace_arr = array(
      '(' => '',
      ')' => '',
      '.' => '',
      ',' => ''
    );
    $resname = strtr($resname, $replace_arr);

    $resname_arr = explode(' ', $resname);

    //Пытаемся определить категорию.
    $categories = array();
    foreach($catarr AS $key_category => $val_category)
    {
      //Проверяем по полному совпадению
      foreach($resname_arr AS $value)
      {
        if(mb_strlen($value) > 1 AND in_array($value, $val_category))
        {
          if(!isset($categories[$key_category]))
            $categories[$key_category] = 0;

          $categories[$key_category]++;
        }
      }

      //Проверяем по составным ключевым словам
      foreach($val_category AS $keycat)
      {
        $keycat = trim($keycat);
        //Если в слове-ключе пробелы
        if(mb_substr_count($keycat, ' ') > 0)
        {
          if(mb_substr_count($resname, $keycat) > 0)
          {
            if(!isset($categories[$key_category]))
              $categories[$key_category] = 0;

            $categories[$key_category]++;
          }
        }
      }
    }

    $cat_id = 0;
    if(count($categories) > 0)
    {
      //Если попала одна категория
      if(count($categories) == 1)
      {
        foreach($categories AS $key => $val)
          $cat_id = $key;
      }
      else
      {
        //Если совпадения по нескольким категориям пытаемся выбрать ту, у которой большинство
        $max_val = 0;
        $max_idval = 0;
        //Находим наибольшее значение
        foreach($categories AS $key => $val)
        {
          if($val > $max_val)
          {
            $max_val = $val;
            $max_idval = $key;
          }
        }

        //Находим число совпедений
        $max_count = 0;
        foreach($categories AS $key => $val)
        {
          if($max_val == $val)
            $max_count ++;
        }

        //Если макс значение одно - выбираем его за катеорию.
        if($max_count == 1)
          $cat_id = $max_idval;
      }
    }

    return $cat_id;
  }
}