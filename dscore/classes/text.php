<?php
defined('DS_ENGINE') or die('web_demon laughs');

class text
{
  private static $call_spoiler;
  private static $call_presentstion;
  private static $numb_spoiler;
  private static $numb_presentstion;
  private static $call_presave;
  private static $code = array();
  private static $code_num;
  private static $post_id;
  private static $hed_tags;
  private static $fin_tags;
  private static $reg_count;
  private static $hed_regx;
  private static $fin_regx;
  private static $now_reg;
  private static $smile_limit = 5;

  private static $codelangs = array(
    'asm', 'asp', 'basic', 'c', 'cpp',
    'css', 'delphi', 'html', 'java',
    'javascript', 'mysql', 'pascal',
    'perl', 'php', 'python', 'sql',
    'xml', 'bash', 'csharp');

  private static $colors = array(
    'red', 'green', 'blue', 'azure',
    'yellow', 'gold', 'black', 'white');

  /**
   * Функция для удаления бб-кодов
   *
   * @param string $string - текст для обработки
   * @param int $len - длина возвращаемой строки (следует не точно, поправка на htmlentities)
   * @return string
   */
  public static function cut($string, $len = 0)
  {
    $string = preg_replace_callback('#\[code\](.+?)\[\/code\](\<br \/\>)?|\[code(\=|\ )(' . implode('|', self::$codelangs) . ')\](.+?)\[\/code\](\<br \/\>)?#si', array('self', 'highlight_del'), $string);
    $string = preg_replace('#\[b\](.+?)\[/b\]#si', '\1', $string);
    $string = preg_replace('#\[i\](.+?)\[/i\]#si', '\1', $string);
    $string = preg_replace('#\[u\](.+?)\[/u\]#si', '\1', $string);
    $string = preg_replace('#\[s\](.+?)\[/s\]#si', '\1', $string);
    $string = preg_replace_callback('#\[c\](.+?)\[/c\]|\[c\=([0-9a-zа-яё\-\@\!\_]{3,15})\](.+?)\[/c\]#sui', array('self', 'quote_del'), $string);
    $string = preg_replace('/\[color(\=|\ )(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\](.+?)\[\/color\]/si', '\3', $string);
    $string = preg_replace('/\[bg(\=|\ )(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\](.+?)\[\/bg\]/si', '\3', $string);
    $string = preg_replace('#\[sup\](.+?)\[/sup\]#si', '\1', $string);
    $string = preg_replace('/\[size(\=|\ )([0-9]{2})\](.+?)\[\/size\]/si', '\3', $string);
    $string = preg_replace('/\[pr(\=|\ )([0-9a-zа-яё\-\_\ ]{1,100})\](.+?)\[\/pr\]/si', '\3', $string);
    $string = preg_replace('#\[sub\](.+?)\[/sub\]#si', '\1', $string);
    $string = preg_replace('#\[math\](.+?)\[/math\]#si', '\1', $string);
    $string = preg_replace('#\[head\](.+?)\[/head\]#si', '\1', $string);
    $string = preg_replace('#\[(left|center|right)\](.+?)\[/\1\]#si', '\2', $string);
    $string = preg_replace_callback('#\[spoiler\](.+?)\[\/spoiler\](\<br \/\>)?|\[spoiler(\=|\ )([^\n\]]*)\](.+?)\[\/spoiler\](\<br \/\>)?#si', array('self', 'sp_del_call'), $string);
    $string = preg_replace_callback('~\\[url=(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)~ui', array('self', 'url_replace_del'), $string);
    $string = preg_replace('#\[hr\](\<br \/\>)?#si', '', $string);
    $string = preg_replace('#\[youtube\](.+?)\[/youtube\]#i', '\1', $string);
    $string = preg_replace('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', '\3', $string);
    if($len)
      $string = mb_substr($string, 0, $len);
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
  }

  /**
   * Функция для правильного вывода url, распарсивает в тексте только ссылки
   *
   * @param string $string - текст
   * @return string
   */
  public static function out_url($string)
  {
    $string = nl2br(htmlentities($string, ENT_QUOTES, 'UTF-8'));
    $string = preg_replace_callback('~\\[url=(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)~ui', array('self', 'url_replace'), $string);
    return $string;
  }

  /**
   * Функция для автозавершения бб-кодов (текст не парсит)
   *
   * @param string $string - текст для обработки
   * @param int $lenght - длина возвращаемой строки (следует не точно, делит по пробелам)
   * @return string
   */
  public static function auto_cut($string, $lenght = 0)
  {
    //Подготовка к обрезке текста, убираем лишние пробелы
    $string = htmlentities($string, ENT_QUOTES, 'UTF-8');
    $string = preg_replace('#\[code (' . implode('|', self::$codelangs) . ')\]#si', '[code=\1]', $string);
    $string = preg_replace('/\[size ([0-9]{2})\]/si', '[size=\1]', $string);
    $string = preg_replace('/\[color (\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\]/si', '[color=\1]', $string);
    $string = preg_replace('/\[bg (\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\]/si', '[bg=\1]', $string);
    $string = preg_replace_callback('#\[spoiler ([^\n\]]*)\]#si', array('self', 'save_probels'), $string);
    $string = preg_replace_callback('/\[file\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\]/', array('self', 'save_probels'), $string);
    $string = preg_replace_callback('/\[img\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\]/', array('self', 'save_probels'), $string);

    $str_array = explode(' ', $string);

    //Обрезаем текст по пробелам
    if($lenght AND mb_strlen($string) > $lenght)
    {
      $new_string = '';
      $i = 0;
      while(mb_strlen($new_string) < $lenght)
      {
        $new_string = $new_string . ' ' . $str_array[$i];
        $i++;
      }
      $string = mb_substr($new_string, 1); //Убираем один лишний пробел в начале
    }
    $string = str_replace('<>', ' ', $string);
    $string = html_entity_decode($string, ENT_QUOTES, 'UTF-8');

    //Теги, которые необходимо завершать
    self::$hed_tags = array('[code]', '[spoiler]', '[b]', '[i]', '[s]', '[u]', '[sup]', '[sub]', '[left]', '[center]', '[right]', '[c]', '[math]', '[head]');
    self::$fin_tags = array('[/code]', '[/spoiler]', '[/b]', '[/i]', '[/s]', '[/u]', '[/sup]', '[/sub]', '[/left]', '[/center]', '[/right]', '[/c]', '[/math]', '[/head]');

    //Теги с регулярками
    self::$hed_regx = array(
      '#\[code=(' . implode('|', self::$codelangs) . ')\]#si',
      '/\[color=(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\]/si',
      '/\[bg=(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\]/si',
      '#\[spoiler=([^\n\]]*)\]#si',
      '/\[file\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\]/',
      '/\[img\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\]/',
      '/\[c\=([0-9a-zа-яё\-\@\!\_]{3,15})\]/sui',
      '/\[size\=([0-9]{2})\]/si',
      '/\[pr\=([0-9a-zа-яё\-\_\ ]{1,100})\]/sui',
      '/\[url\=(https?:\/\/(www\.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё\/\?\.\~&amp;_\=\/\%\-\:\#]*)\]/sui'
    );

    self::$fin_regx = array('[/code]', '[/color]', '[/bg]', '[/spoiler]', '[/file]', '[/img]', '[/c]', '[/size]', '[/pr]', '[/url]');

    self::$reg_count = 0;
    $i = 0;
    while(isset(self::$hed_regx[$i]) AND self::$hed_regx[$i])
    {
      self::$reg_count = $i;
      preg_replace_callback(self::$hed_regx[$i], array('self', 'regx2tags'), $string);
      $i++;
    }

    //Автозавершение тегов
    $tags = array();
    $i = 0;
    while(isset(self::$hed_tags[$i]) AND self::$hed_tags[$i])
    {
      $pos = strrpos($string, self::$hed_tags[$i]);
      $pos_fin = strrpos($string, self::$fin_tags[$i]);
      if(!$pos_fin)
        $pos_fin = -1;
      if($pos!==false)
      {
        if($pos > $pos_fin)
          $tags[$pos] = self::$fin_tags[$i];
      }
      $i++;
    }
    ksort($tags);
    $tags = array_reverse($tags);
    $tags = implode('', $tags);

    return $string . $tags;
  }

  /**
   * Функция для обработки бб-кодов
   *
   * @param string $string - текст для обработки
   * @param int $post_rights - права владельца текста
   * @param int $post_id (не использовать!) ID поста
   * @return string
   */
  public static function out($string, $post_rights, $post_id = 0)
  {
    self::$call_presave = false;
    self::$code_num = 0;
    self::$post_id = $post_id;
    //Коды, выпарсиваем и запоминаем

    $string = preg_replace_callback('#\[code\](.*?)\[\/code\]|\[code(\=|\ )(' . implode('|', self::$codelangs) . ')\](.*?)\[\/code\]#si', array('self', 'highlight'), $string);

    //парсим смайлы
    $arr_smile = core::$smiles;
    if($post_rights)
      $arr_smile = array_merge(core::$smiles, core::$smiles_adm);
    $string = self::replace_smiles($string, $arr_smile);

    //Обрабатываем текст
    $string = str_replace("\r\n", "\n", $string);
    $string = "\n".$string;
    $string  = trim(preg_replace_callback('/\n([\ ]{1,10})/si', array('self', 'new_par'), self::st($string)));
    $string = str_replace("\n", '<br />', $string);

    $string = preg_replace('#\[code\|' . core::$smile_code . '\]\[/code\]?#si', '<code|' . core::$smile_code . '></code>', $string);

    //Парсим остальные теги
    $string = self::preg_replace_mod('#\[b\](.*?)\[/b\]#si', '<span style="font-weight:bold;">\1</span>', $string, 1);
    $string = preg_replace_callback('#\[youtube\]([0-9a-z\-\_]{11})\[/youtube\]|\[youtube\]https?\:\/\/youtu.be\/([0-9a-z\-\_]{11})\[/youtube\]|\[youtube\]https?\:\/\/www\.youtube\.com\/watch\?v\=([0-9a-z\-\_]{11})([0-9a-z\-\_\&\;\=]*?)\[/youtube\]#si', array('self', 'youtube'), $string);
    $string = self::preg_replace_mod('#\[i\](.*?)\[/i\]#si', '<span style="font-style:italic;">\1</span>', $string, 1);
    $string = self::preg_replace_mod('#\[u\](.*?)\[/u\]#si', '<span style="text-decoration:underline;">\1</span>', $string, 1);
    $string = self::preg_replace_mod('#\[s\](.*?)\[/s\]#si', '<span style="text-decoration:line-through;">\1</span>', $string, 1);
    $string = preg_replace_callback('#\[c\](.*?)\[/c\](\<br \/\>)?|\[c\=([0-9a-zа-яё\-\@\!\_]{3,15})\](.*?)\[/c\](\<br \/\>)?#sui', array('self', 'quote'), $string);
    $string = self::preg_replace_mod('/\[color(\=|\ )(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\](.*?)\[\/color\]/si', '<span style="color:\2">\3</span>', $string, 3, 2);
    $string = self::preg_replace_mod('/\[bg(\=|\ )(\#[0-9a-f]{6}|' . implode('|', self::$colors) . ')\](.*?)\[\/bg\]/si', '<span style="background:\2;padding:2px;border-radius:3px;">\3</span>', $string, 3, 2);
    $string = preg_replace_callback('/\[size(\=|\ )([0-9]{1,2})\](.*?)\[\/size\]/si', array('self', 'size_tag'), $string);
    $string = self::preg_replace_mod('#\[head\](.*?)\[/head\](\<br \/\>)?#si', '<span style="font-weight:bold;font-size:18px;display:block;margin-bottom: 4px;">\1</span>', $string, 1);
    $string = self::preg_replace_mod('#\[sup\](.*?)\[/sup\]#si', '<span style="vertical-align:super">\1</span>', $string, 1);
    $string = self::preg_replace_mod('#\[sub\](.*?)\[/sub\]#si', '<span style="vertical-align:sub">\1</span>', $string, 1);
    $string = self::preg_replace_mod('#\[(left|center|right)\](.*?)\[/\1\](\<br \/\>)?#si', '<span style="display:block;text-align:\1">\2</span>', $string, 2);
    $string = preg_replace('#\[hr\](\<br \/\>)?#si', '<hr class="intext"/>', $string);

    //Парсинг ссылок
    $string = preg_replace_callback('~\\[url=(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)\\](.+?)\\[/url\\]|(https?://(www.)?[0-9a-zа-яё\.-]+\.[0-9a-zа-яё]{2,6}[0-9a-zа-яё/\?\.\~&amp;_=/%-:#]*)~ui', array('self', 'url_replace'), $string);

    $string = preg_replace_callback('#\[math\](.*?)\[/math\]#si', array('self', 'math2image'), $string);
    $string = preg_replace_callback('#\[youtube\=' . core::$smile_code . '\]([0-9a-z\-\_]{11})\[/youtube\]#si', array('self', 'youtube_return'), $string);
    $string = preg_replace('#\[smile\:\:([0-9]+?)\.(png|gif)\|' . core::$smile_code . '\]#si', '<img src="' . core::$home . '/images/smiles/\1.\2"/>', $string);
    $string = preg_replace_callback('#\[spoiler\](.*?)\[\/spoiler\](\<br \/\>)?|\[spoiler(\=|\ )([^\n\]]*)\](.*?)\[\/spoiler\](\<br \/\>)?#sui', array('self', 'sp_call'), $string);
    $string = preg_replace_callback('#\[pr\](.*?)\[\/pr\](\<br \/\>)?|\[pr(\=|\ )([0-9a-zа-яё\-\_\ ]{1,100})\](.*?)\[\/pr\](\<br \/\>)?#sui', array('self', 'presentation_call'), $string);
    $string = preg_replace_callback('#<code\|' . core::$smile_code . '\>\</code\>(\<br \/\>)?#si', array('self', 'highlight_ret'), $string);
    self::$code = array();
    return $string;
  }

  /**
   * Функция для замены смайлов
   *
   * @param string $string - текст для обработки
   * @param array $arr_smile - массив со смайлами
   * @return string
   */
  private function replace_smiles($string, $arr_smile)
  {
    $limit = self::$smile_limit;

    $pos_arr = array();
    $count_arr = array();

    foreach ($arr_smile AS $key => $value)
    {
      $found = substr_count($string, $key);
      if($found)
      {
        $pos_arr[$key] = strpos($string, $key);
        $count_arr[$key] = $found;
      }
    }

    natsort($pos_arr);

    foreach($pos_arr AS $key=>$value)
    {
      if($limit > 0)
      {
        if($count_arr[$key] > $limit)
          $string = self::str_replace2($key, $arr_smile[$key], $string, $limit);
        else
          $string = self::str_replace2($key, $arr_smile[$key], $string, $count_arr[$key]);
        $limit = $limit - $count_arr[$key];
      }
    }

    return $string;
  }

  /**
   * Аналог str_replace c лимитом замен
   *
   * @param string $find - что заменить
   * @param string $replacement - на что заменить
   * @param string $subject - где заменить
   * @param int $limit - лимит замен
   * @return string
   */
  private static function str_replace2($find, $replacement, $subject, $limit = 0){
    if ($limit == 0)
      return str_replace($find, $replacement, $limit);
    $ptn = '/' . preg_quote($find, '/' ) . '/';
    return preg_replace($ptn, $replacement, $subject, $limit);
  }

  /**
   * Оболочка для htmlentities
   *
   * @param string $string - текст для обработки
   * @return string
   */
  public static function st($string)
  {
    return htmlentities($string, ENT_QUOTES, 'UTF-8');
  }

  /**
   * Расширенная функция preg_replace, возвращает нетронутый текст соответствующей маске если тот пустой,
   * Иначе возвращает текст с маской, замененной на $replace
   *
   * @param string $reg - регулярное выражение
   * @param string $replace - строка замены
   * @param string $string - текст для обработки
   * @param int $max_reg - маска по счету, по которой нужно судить пустой ли тег
   * @param int $start - маска, с которой следует начинать поиск
   * @return string
   */
  private static function preg_replace_mod($reg, $replace, $string, $max_reg, $start = 1)
  {
    self::$now_reg = array($reg, $replace, $string, $max_reg, $start);
    $string = preg_replace_callback($reg, array('self', 'help_preg'), $string);
    return $string;
  }

  /**
   * Функция записывает кэш тэга [code][/code] в cтроку
   *
   * @param string $string - текст для обработки
   * @param boolean $nozip - нужно ли упаковывать
   * @return string
   */
  public static function presave($string, $nozip = false)
  {
    self::$call_presave = true;
    self::$code = array();
    preg_replace_callback('#\[code\](.*?)\[\/code\]|\[code(\=|\ )(' . implode('|', self::$codelangs) . ')\](.*?)\[\/code\]#si', array('self', 'highlight'), $string);
    $out = serialize(self::$code);
    if(!$nozip)
      $out = gzdeflate($out);
    if(self::$code)
      return $out;
    else
      return '';
  }

  /**
   * Записывает подгружает кэш [code][/code] из cтроки
   *
   * @param string $string - кэш
   * @param boolean $nozip - упакована ли строка
   * @return string
   */
  public static function add_cache($string, $nozip = false)
  {
    if($string)
    {
      if(!$nozip)
        $string = gzinflate($string);
      self::$code = unserialize($string);
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Замена тега [pr][/pr] на презентацию
   *
   * @param array $m - массив с совпадениями
   * @return string
   */
  private static function presentation_call($m)
  {
    if(!empty($m[1]))
    { // Если название презентации не задано
      $name = lang('pr_tag_head');
      $content = $m[1];
    }
    else
    { //Если название презентации задано
      $name = preg_replace('#\<a href\=\"(.+?)\"\>(.+?)\<\/a\>#si', '\2', $m[4]); //Убираем ссылки
      $content = $m[5];
    }

    //Если между тегами только пробелы и переносы, ничего не парсим
    if(!isset($content) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $content)))) <= 0)
      return $m[0];

    $content_arr = explode('[sl]', $content);
    $slides_arr = array();
    $one_call = 0;
    foreach($content_arr AS $value)
    {
      if(mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $value)))) > 0)
      {
        //Удаляем отступы в начале и в конце, дабы дать народу некоторую свободу форматирования
        $value = trim(str_replace('<br />', "\n", $value));
        $value = str_replace("\n", '<br />', $value);

        $slides_arr[] = '<span class="slide" style="display: '.($one_call ? 'none' : 'block').';">'.$value.'</span>';
        if(!$one_call)
          $one_call = 1;
      }
    }

    //Если все слайды пустые, то прекращаем парсинг
    if(!$slides_arr)
      return $m[0];

    self::$numb_presentstion++;
    $out = '';

    if(!self::$call_presentstion)
    {
      $out .= '<script src="' . core::$home . '/data/js/presentation.js"></script>';
      self::$call_presentstion = 1;
    }

    $out .= '
    <div class="pr_head"><table><tr><td class="switch">
    <span class="controls">
        <a onclick="prevSlide(\'presentation'.self::$numb_presentstion.'\',\'slidenumber'.self::$numb_presentstion.'\')"><i class="icon-backward"></i></a>
        <span id="slidenumber'.self::$numb_presentstion.'" class="slidenumber">1</span> '.lang('pr_add_sm').' '.count($slides_arr).'
        <a onclick="nextSlide(\'presentation'.self::$numb_presentstion.'\',\'slidenumber'.self::$numb_presentstion.'\')"><i class="icon-forward"></i></a>
    </span></td><td class="tthead"><i class="icon-squares"></i>'.$name.'</td></tr></table></div>';

    return $out.'<span id="presentation'.self::$numb_presentstion.'" class="presentation">'.implode('', $slides_arr).'</span>';
  }

  /**
   * Декодирование htmlentities
   *
   * @param string $string - строка для обработки
   * @return string
   */
  public static function unhtmlentities($string)
  {
    $string = str_replace('&amp;', '&', $string);
    $string = preg_replace('~&#x0*([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
    $string = preg_replace('~&#0*([0-9]+);~e', 'chr(\\1)', $string);
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
  }

  /**
   * Замена тега [spoiler][/spoiler] на спойлер
   *
   * @param array $m - массив с совпадениями
   * @return string
   */
  private static function sp_call($m)
  {
    $numb = self::$numb_spoiler;
    self::$numb_spoiler++;
    $out = '';
    if(!self::$call_spoiler)
    {
      $out = '<script src="' . core::$home . '/data/js/spoiler.js"></script>';
      self::$call_spoiler = 1;
    }

    if(!empty($m[5]))
    {
      $text = $m[5];

      if(!isset($m[5]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[5])))) <= 0)
        return $out . $m[0];

      $sp_close = preg_replace('#\<a href\=\"(.+?)\"\>(.+?)\<\/a\>#si', '\2', $m[4]);
      $sp_open = preg_replace('#\<a href\=\"(.+?)\"\>(.+?)\<\/a\>#si', '\2', $m[4]);
    }
    else
    {
      $text = $m[1];

      if(!isset($m[1]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[1])))) <= 0)
        return $out . $m[0];

      $sp_open = lang('sp_open');
      $sp_close = lang('sp_close');
    }

    //удаляем лишний перенос строки
    $text = trim($text);
    $ifbr = mb_substr($text, 0, 6);
    if($ifbr == '<br />')
      $text = mb_substr($text, 6);

    return $out . '<a href="javascript:show(\'a' . $numb . '\',\'b' . $numb . '\',\'c' . $numb . '\')"><div class="tops" id="b' . $numb . '">' . $sp_open . '</div></a><a href="javascript:show(\'a' . $numb . '\',\'b' . $numb . '\',\'c' . $numb . '\')"><div class="tops2" id="c' . $numb . '" style="display:none">' . $sp_close . '</div></a><span id="a' . $numb . '" style="display:none" class="spoiler">' . $text . '</span>';
  }

  /**
   * Корректное удаление тега [spoiler][/spoiler] из текста
   *
   * @param array $m - массив с совпадениями
   * @return string
   */
  private static function sp_del_call($m)
  {
    if(!empty($m[5]))
      $text = $m[5];
    else
      $text = $m[1];

    return ' ' . $text . ' ';
  }

  /**
   * Замена тега [code][/code] (подсветка кода)
   *
   * @param array $c - массив с совпадениями
   * @return string - возвращает псевдо-тег
   */
  private static function highlight($c)
  {
    if(self::$call_presave)
    {
      $language = '';
      if($c[3])
      {
        $code = $c[4];
        if(!isset($c[4]) OR mb_strlen(trim($c[4])) <= 0)
          return $c[0];
        $language = $c[3];
      }
      else
      {
        $code = $c[1];
        if(!isset($c[1]) OR mb_strlen(trim($c[1])) <= 0)
          return $c[0];
      }
      if(!$language)
        $language = 'php';

      //обрабатываем код - убираем все лишнее
      $code = self::rn2n($code);
      $code = self::trim_code($code);
      $code = self::check_code($code);

      include_once('dscore/libs/geshi.php');
      $geshi = new GeSHi($code, $language);
      $geshi->set_header_type(GESHI_HEADER_DIV);
      $geshi->enable_line_numbers(GESHI_FANCY_LINE_NUMBERS, 1);
      array_push(self::$code, $geshi->parse_code());
    }
    else
    {
      if($c[3])
      {
        if(!isset($c[4]) OR mb_strlen(trim($c[4])) <= 0)
          return $c[0];
      }
      else
      {
        if(!isset($c[1]) OR mb_strlen(trim($c[1])) <= 0)
          return $c[0];
      }
    }

    return '[code|' . core::$smile_code . '][/code]';
  }

  /**
   * Удаление перевода каретки из переноса строки
   *
   * @param string $code - текст для обработки
   * @return string
   */
  private static function rn2n($code)
  {
    return str_replace("\r\n", "\n", $code);
  }

  /**
   * Удаление пустых строк (именно строк!) в начале и конце текста
   *
   * @param string $code - текст для обработки
   * @return string
   */
  private static function trim_code($code)
  {
    $code = explode("\n", $code);
    $code_count = count($code);
    if($code_count > 0)
    {
      //Удаляем пробелы и пустые строки в начале кода
      $i = 0;
      while(isset($code[$i]) AND trim($code[$i]) == '')
      {
        unset($code[$i]);
        $i++;
      }
      //Теперь удаляем в конце =)
      $i = 0;
      while(isset($code[$code_count - 1 - $i]) AND trim($code[$code_count - 1 - $i]) == '')
      {
        unset($code[$code_count - 1 - $i]);
        $i++;
      }
    }
    return implode("\n", $code);
  }

  /**
   * Удаление лишних пробелов перед форматированным кодом
   *
   * @param string $code - текст для обработки
   * @return string
   */
  private static function check_code($code)
  {
    $code = explode("\n", $code);
    $spaces = array();
    $code_count = count($code);
    if($code_count > 0)
    {
      foreach($code AS $key => $value)
      {
        if(trim($value) != '')
        {
          $letters = str_split($value);
          $i = 0;
          while($letters[$i] == ' ')
          {
            $i++;
          }
          $spaces[] = $i;
        }
        else
          $code[$key] = trim($value);
      }
      if(count($spaces) > 0)
      {
        $min_sp = min($spaces);
        if($min_sp > 0)
        {
          $new_code = array();
          foreach($code AS $value)
          {
            if($value)
              $new_code[] = mb_substr($value, $min_sp);
            else
              $new_code[] = $value;
          }
          $code = $new_code;
        }
      }
    }
    return implode("\n", $code);
  }

  /**
   * Замена псевдо-тега [code]
   *
   * @param array $c - масив с совпадениями
   * @return string
   */
  private static function highlight_ret($c)
  {
    self::$code_num++;
    return '<div class="backcode">' . array_shift(self::$code) . '</div>';
  }

  /**
   * Корректное удаление тега [code][/code]
   *
   * @param array $c - масив с совпадениями
   * @return string
   */
  private static function highlight_del($c)
  {
    if(!empty($c[5]))
      $code = $c[5];
    else
      $code = $c[1];
    return $code;
  }

  /**
   * Корректное удаление тега [c][/c]
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function quote_del($m)
  {
    if($m[1])
      return $m[1];
    else
      return $m[3];
  }

  /**
   * Вспомогательная функция для парсинга ссылок
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function url_replace($m)
  {
    if(!isset ($m[4]))
    {
      $url = parse_url($m[1]);
      if($url['host'] != core::$home)
        $m[1] = core::$home . '/url?out=' . rawurlencode($m[1]);
      return '<a href="' . $m[1] . '">' . $m[3] . '</a>';
    }
    else
    {
      if(mb_strlen($m[4]) > 70)
      {
        $u1 = mb_substr($m[4], 0, 30);
        $u2 = mb_substr($m[4], (mb_strlen($m[4]) - 20), 20);
        $show_url = $u1 . '...' . $u2;
      }
      else
        $show_url = $m[4];

      $url = parse_url($m[4]);
      if($url['host'] != core::$home)
        $m[4] = core::$home . '/url?out=' . $m[4];
      return '<a href="' . $m[4] . '">' . $show_url . '</a>';
    }
  }

  /**
   * Корректное удаление тега [url][/url]
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function url_replace_del($m)
  {
    if(!isset ($m[4]))
      return $m[3];
    else
      return $m[4];
  }

  /**
   * Замена тега [youtube][/youtube] на псевдо-тег
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function youtube($m)
  {
    if($m[1])
      $out = $m[1];
    elseif($m[2])
      $out = $m[2];
    else
      $out = $m[3];
    return '[youtube=' . core::$smile_code . ']' . $out . '[/youtube]';
  }

  /**
   * Замена псевдо-тега [youtube] на видеоролик youtube
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function youtube_return($m)
  {
    //Переделать, чтоб в каждой теме был свой шаблон
    new mail_temp('./data/engine/');
    mail_temp::assign('link', $m[1]);
    mail_temp::assign('home', core::$home);
    mail_temp::assign('lang_view', lang('yout_view'));

    if(core::$type_display == 'wap')
      return mail_temp::get('youtube_mobile');
    else
      return mail_temp::get('youtube');
  }

  /**
   * Вспомогательная функция для обработки цитат
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function quote($m)
  {
    if($m[1])
    {
      if(!isset($m[1]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[1])))) <= 0)
        return $m[0];
      else
        return '<span class="quote">' . $m[1] . '</span>';
    }
    else
    {
      if(!isset($m[4]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[4])))) <= 0)
        return $m[0];
      else
        return '<span class="quote"><small>Цитата <b>' . $m[3] . ':</b></small><hr class="mail"/>' . $m[4] . '</span>';
    }
  }

  /**
   * Функция позволяет избежать разбивки текста по бб-коду, заменяет пробелы на символы <>,
   * которые не могут находится в обработанном тексте htmlentities
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function save_probels($m)
  {
    $str = $m[1];
    $new_str = str_replace(' ', '<>', $str);
    $out = str_replace($m[1], $new_str, $m[0]);
    return str_replace(' ', '=', $out);
  }

  /**
   * Конвертирование регулярных выражений во временные локальные бб-коды
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function regx2tags($m)
  {
    array_push(self::$hed_tags, $m[0]);
    array_push(self::$fin_tags, self::$fin_regx[self::$reg_count]);
    return $m[0];
  }

  /**
   * Функция для обработки LaTeX
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static function math2image($m)
  {
    if(!isset($m[1]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[1])))) <= 0)
      return $m[0];
    else
    {
      $m[1] = str_replace('<br />', '', $m[1]);
      return '<img valign="baseline" src="http://chart.apis.google.com/chart?cht=tx&chf=bg,s,00000000&chl=' . urlencode(self::unhtmlentities($m[1])) . '"/>';
    }
  }

  /**
   * Функция для обработки тега [size][/size]
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static  function size_tag($m)
  {
    if(!isset($m[3]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[3])))) <= 0)
      return $m[0];
    elseif($m[2] > 20 OR $m[2] < 10)
      return $m[0];
    else
      return '<span style="font-size:'.$m[2].'px;">' . $m[3] . '</span>';
  }

  /**
   * Функция для сохранения отступа текста
   * По сути оставляет пробелы в начале строки (есть лимит замены в регулярке)
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private static  function new_par($m)
  {
    return "\n".str_repeat('&nbsp;', mb_strlen($m[1])).' ';
  }

  /**
   * Функция для определения наличия в маске отличных от пробельных символов
   * При нахождении продолжает парсинг и выводит текст
   * При отсуствии возвращает исходную маску
   *
   * @param array $m - масив с совпадениями
   * @return string
   */
  private function help_preg($m)
  {
    $loc_error = false;
    for($i = self::$now_reg[4]; $i <= self::$now_reg[3]; $i++)
    {
      if(!isset($m[$i]) OR mb_strlen(trim(self::unhtmlentities(str_replace("<br />", ' ', $m[$i])))) <= 0)
        $loc_error = true;
    }

    if($loc_error)
      return $m[0];
    else
      return preg_replace(self::$now_reg[0], self::$now_reg[1], $m[0]);
  }

  /**
   * Функция обрабатывает текст для вывода цитаты
   *
   * @param string $text - текст для обработки
   * @return string
   */
  public static function to_quote($text)
  {
    $text = preg_replace('/\[(file|img)\=([^\n\&\/\"\\\\<\>\+\&\;\:]{1,200})\](.*?)\[\/\1\]/', '\3', $text);
    $text = preg_replace('#\[c\](.+?)\[/c\]|\[c\=([0-9a-zа-яё\-\@\!\_]{3,15})\](.+?)\[/c\]#sui', '', $text);
    $text = mb_substr($text, 0, 200);
    $text = str_replace("'", '&apos;', $text);
    $text = addslashes(self::st($text));
    return str_replace("\n", '\n', $text);
  }
}


