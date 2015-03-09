<?php

class func
{
  static function img_capcha()
  {
    return '<img width="100" height="50" src="' . core::$home . '/captcha.php?r=' . rand(1000, 9999) . '" alt="code" />';
  }

  static function capcha()
  {
    if(!empty($_SESSION['code']))
    {
      if(POST('vcode') != $_SESSION['code'])
      {
        unset($_SESSION['code']);
        return FALSE;
      }
      else
      {
        unset($_SESSION['code']);
        return TRUE;
      }
    }
    else
      return FALSE;
  }

  static function notify($header, $notify, $link, $buttontext = '')
  {
    if(core::$typetheme == 'web' AND core::$user_id)
    {
      uscache::rem('mess_head', $header);
      uscache::rem('mess_body', $notify);

      header('Location:' . $link);
      exit();
    }
    else
    {
      engine_head($header);
      temp::assign('header', $header);
      temp::assign('notify', $notify);
      temp::assign('link', $link);
      if(!$buttontext)
        $buttontext = lang('continue');
      temp::assign('buttontext', $buttontext);
      temp::display('notify');
      engine_fin();
      exit();
    }
  }

  static function passgen($length)
  {
    $vals = "abcdefghijklmnopqrstuvwxyz0123456789";
    $result = '';
    for($i = 1; $i <= $length; $i++)
    {
      $result .= $vals{rand(0, mb_strlen($vals) - 1)};
    }
    return $result;
  }

  static function tagspanel($msg = '')
  {
    // Часть кода была взята из johncms (johncms.com)
    // Однако не стоит забывать, на aNNiMON.com эта фишка появилась раньше.
    // Функция tag была найдена на просторах интернета, модифицорована до нормального состояния
    if(!$msg)
      $msg = 'msg';

    $colors = array(
      'ffffff', 'bcbcbc', '708090', '6c6c6c', '454545',
      'fcc9c9', 'fe8c8c', 'fe5e5e', 'fd5b36', 'f82e00',
      'ffe1c6', 'ffc998', 'fcad66', 'ff9331', 'ff810f',
      'd8ffe0', '92f9a7', '34ff5d', 'b2fb82', '89f641',
      'b7e9ec', '56e5ed', '21cad3', '03939b', '039b80',
      'cac8e9', '9690ea', '6a60ec', '4866e7', '173bd3',
      'f3cafb', 'e287f4', 'c238dd', 'a476af', 'b53dd2'
    );

    $prog_langs = array(
      'asm' => 'Asm',
      'xml' => 'XML',
      'basic' => 'Basic',
      'csharp' => 'C#',
      'cpp' => 'C++',
      'css' => 'CSS',
      'delphi' => 'Delphi',
      'html' => 'Html',
      'java' => 'Java',
      'javascript' => 'JS',
      'mysql' => 'MySql',
      'pascal' => 'Pascal',
      'perl' => 'Perl',
      'php' => 'PHP',
      'python' => 'Python',
      'bash' => 'bash'
    );

    if(core::$type_display == 'web')
    {
      $prog = '<div class="bb_hide_lang bb_color"><div>';
      foreach($prog_langs as $key => $value)
      {
        $prog .= '<a class="lang" href="javascript:tag(\'[code ' . $key . ']\', \'[/code]\');">' . $value . '</a>';
      }
      $prog .= '</div></div>';

      $font_color = '<div class="bb_hide bb_color"><div>';
      $bg_color = '<div class="bb_hide bb_color"><div>';
      foreach($colors as $value)
      {
        $font_color .= '<a href="javascript:tag(\'[color=#' . $value . ']\', \'[/color]\');" style="background-color:#' . $value . ';"></a>';
        $bg_color .= '<a href="javascript:tag(\'[bg=#' . $value . ']\', \'[/bg]\');" style="background-color:#' . $value . ';"></a>';
      }
      $font_color .= '</div></div>';
      $bg_color .= '</div></div>';
    }
    else
    {
      $prog = '';
      $font_color = '';
      $bg_color = '';
    }
    $lang_bb = core::parse_lang('languages/' . core::$lang . '/__add/bblang.lang');

    new mail_temp('./data/engine/');
    foreach($lang_bb AS $key => $value)
    {
      mail_temp::assign($key, $value);
    }
    mail_temp::assign('theme_path', core::$theme_path);
    mail_temp::HTMassign('prog', $prog);
    mail_temp::HTMassign('font_color', $font_color);
    mail_temp::HTMassign('bg_color', $bg_color);
    mail_temp::assign('msg', $msg);

    return mail_temp::get('bbpanel');
  }

  static function antiflood()
  {
    //Взято из johnCMS http://johncms.com

    ////////////////////////////////////////////////////////////
    // Глобальная система Антифлуда                           //
    ////////////////////////////////////////////////////////////
    // Режимы работы:                                         //
    // 1 - Адаптивный                                         //
    // 2 - День / Ночь                                        //
    // 3 - День                                               //
    // 4 - Ночь                                               //
    ////////////////////////////////////////////////////////////

    $default = array(
      'mode' => 2,
      'day' => 10,
      'night' => 30,
      'dayfrom' => 10,
      'dayto' => 22
    );
    $af = isset(core::$set['antiflood']) ? unserialize(core::$set['antiflood']) : $default;
    switch($af['mode'])
    {
      case 1:
        // Адаптивный режим
        $onltime = time() - core::$set['onlinetime'];
        $req = core::$db->query('SELECT COUNT(*) FROM `ds_users` WHERE `rights` > 0 AND `lastdate` > "' . $onltime . '"');
        $adm = $req->count();
        $limit = $adm > 0 ? $af['day'] : $af['night'];
        break;
      case 3:
        // День
        $limit = $af['day'];
        break;
      case 4:
        // Ночь
        $limit = $af['night'];
        break;
      default:
        // По умолчанию день / ночь
        $c_time = date('G', time());
        $limit = $c_time > $af['day'] && $c_time < $af['night'] ? $af['day'] : $af['night'];
    }
    if(core::$rights > 0)
      $limit = 4; // Для Администрации задаем лимит в 4 секунды
    $flood = core::$last_post + $limit - time();
    if($flood > 0)
      return $flood;
    else
      return false;
  }

  static function getextension($string)
  {
    $n = strrpos($string, ".");
    if($n)
    {
      $n = $n + 1;
      $ext = mb_strtolower(substr($string, $n));
      return $ext;
    }
    else
      return '';
  }

  static function denied_mail($mail)
  {
    $pos = mb_strpos($mail, '@');
    $domain = mb_substr($mail, $pos + 1);

    $query = 'SELECT * FROM `ds_forbidden_mails` WHERE `domain` = "' . core::$db->res($domain) . '";';
    $query .= 'SELECT * FROM `ds_depr_emails` WHERE `mail` = "' . core::$db->res($mail) . '";';

    core::$db->multi_query($query);

    $result = core::$db->store_result()->fetch_row();
    $f_domen = $result[0];

    core::$db->next_result();
    $result = core::$db->store_result()->fetch_row();
    $f_mail = $result[0];

    if($f_mail or $f_domen)
      return TRUE;
    else
      return FALSE;
  }
}
