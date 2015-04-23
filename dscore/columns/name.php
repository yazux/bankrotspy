<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_name
{
  private $name;
  private $lenght;
  private $attr;
  private $item_arr;
  private $replaced = 0;
  private $descr;

  function __construct($params)
  {
    $this->name =isset($params[0]) ?  trim($params[0]) : '';
    $this->lenght = isset($params[1]) ? $params[1] : 0;
    $this->attr = isset($params[2]) ? $params[2] : '';
    $this->item_arr = isset($params[3]) ? $params[3] : array();
    $this->descr = isset($params[4]) ? trim($params[4]) : '';
  }

  public function before_load()
  {
    return array(
      'sortcolumn' => ' `ds_maindata`.`name` '
    );
  }

  public function name()
  {
    return array(
      'name' => 'Лот',
      'style' => 'max-width: 200px;'
    );
  }

  private function highlight_founded($m)
  {
    return '<span class="search_highlight">'.$m[0].'</span>';
  }

  private function replace_finded($text, $words_arr)
  {
    foreach($words_arr AS $val)
    {
      $val = text::st($val);
      if(preg_match('/' . preg_quote($val) . '/ui', $text))
      {
        $text = preg_replace_callback('/' . preg_quote($val) . '/ui', array($this, 'highlight_founded'), $text);
        $this->replaced++;
      }
    }
    return $text;
  }

  private function check_finded($text, $words_arr)
  {
    $found = 0;
    foreach($words_arr AS $val)
    {
      $val = text::st($val);
      if(preg_match('/' . preg_quote($val) . '/ui', $text))
        $found++;
    }
    return $found;
  }

  private function replace_onbr($lotname)
  {
    if(mb_substr_count($lotname, ' | '))
      $lotname = '- '.str_replace(' | ', '<br/>- ', $lotname);
    return $lotname;
  }

  public function process()
  {
    if(!$this->name)
      $name = $this->descr;
    elseif($this->name != $this->descr)
    {
      if(mb_substr_count($this->descr, $this->name))
        $name = $this->descr;
      else
        $name = trim($this->name) . '. ' . $this->descr;
    }
    else
      $name = $this->name;

    $full_name = $name;

    $now_lenght = mb_strlen($name);

    $cutted = false;
    if($now_lenght > $this->lenght)
    {
      $name = mb_substr($name, 0, $this->lenght);
      $name = $name.'...';
      $cutted = true;
    }
    $name = text::st($name);

    if($this->item_arr)
    {
      $name = $this->replace_finded($name, $this->item_arr);

      if(!$this->replaced) //Если в обрезанной строке ничего не нашлось, показываем дополнительный кат
      {
        //Определяемся где били найдены наши словечки
        if($this->check_finded(text::st($this->name), $this->item_arr))
          $text = $this->name;
        else
          $text = $this->descr;

        //Отступ в одну и другую стороны
        $symbols_area = 25;

        //Пытаемся отрезать тот кусок, в котором что-то найдено

        foreach($this->item_arr AS $val)
        {
          $val = trim($val);
          $pos = 0;
          if($val)
          {
            //Это какой-то дурдом. Работает и хер с ним.
            preg_match('/' . preg_quote($val) . '/ui', $text, $matches, PREG_OFFSET_CAPTURE);
            if($matches[0][0])
              $pos = mb_strpos($text, $matches[0][0]);
            break;
          }
        }
        $start = $pos + 1 - $symbols_area;
        if($start < 0)
          $start = 0;
        $lenght = $symbols_area * 2;
        $sub_text = mb_substr($text, $start, $lenght);
        $sub_text = text::st($sub_text);
        $sub_text = $this->replace_finded($sub_text, $this->item_arr);

        //Показываем этот кусок
        $addition = '<hr/><span style="color:gray;font-size: 11px">...'.$sub_text.'...</span>';
      }
    }

    return array(
      'col' => '<a target="_blank" class="namelink" href="'.core::$home.'/card/'.$this->attr.'"><i class="icon-share"></i><span id="min_name_'.$this->attr.'">'.$this->replace_onbr($name).'</span><span style="display: none;" id="max_name_'.$this->attr.'">'.$this->replace_onbr(text::st($full_name)).'</span></a>'.($cutted ? '<br/><span attr="'.$this->attr.'" class="show_span">Показать</span>' : '' ).(isset($addition)? $addition : '' ),
      'onlydata' => $this->replace_onbr(text::st($full_name)),
      'style' => 'max-width: 300px;'
    );
  }
}
