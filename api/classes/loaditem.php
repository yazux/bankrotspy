<?php
defined('DS_ENGINE') or die('web_demon laughs');

class loaditem
{
  private static $error;
  private static $id;

  public static function generete_sql($getdata)
  {
    $dataarr = array('');
    $getarr = explode(';', $getdata);
    $dataarr = array_merge($dataarr, $getarr);

    //Избавляемся от кавычек
    $new_drarr = array();
    foreach($dataarr AS $kkey=>$vval)
    {
      $vval = trim($vval);
      $first_symbol = mb_substr($vval, 0, 1);
      $len = mb_strlen($vval);
      $end_symbol = mb_substr($vval, ($len-1), 1);
      if($first_symbol == '"' AND $end_symbol == '"')
      {
        $vval = mb_substr($vval, 1, ($len-2));
      }
      $new_drarr[$kkey] = $vval;
    }
    $dataarr = $new_drarr;

    //Для лучшего понимания
    $sourse = array();
    $sourse['lotname']         = $dataarr[2];
    $sourse['price']           = $dataarr[3];
    $sourse['place']           = $dataarr[4];
    $sourse['type']            = $dataarr[5];
    $sourse['status']          = $dataarr[6];
    $sourse['description']     = $dataarr[7];
    $sourse['begin_date']      = $dataarr[8];
    $sourse['end_date']        = $dataarr[9];
    $sourse['nowprice']        = $dataarr[10];
    $sourse['debtor']          = $dataarr[11];
    $sourse['deblor_card']     = $dataarr[12];
    $sourse['organizer']       = $dataarr[13];
    $sourse['contact_person']  = $dataarr[14];
    $sourse['manager']         = $dataarr[15];
    $sourse['manager_card']    = $dataarr[16];
    $sourse['code']            = $dataarr[17];
    $sourse['auct_link']       = $dataarr[18];
    $sourse['case_number']     = $dataarr[19];
    $sourse['lot_number']      = $dataarr[20];
    $sourse['inn']             = $dataarr[21];
    $sourse['key']             = $dataarr[22];
    $sourse['platform_id']     = $dataarr[23];

    //Лоадер файлов-функций
    $load = new bcdata();
    $error = array();

    //Для вывода
    $outdata = array();

    //Названия те же что в таблице
    $outdata['name'] = trim($sourse['lotname']);
    $outdata['price'] = $load->price($sourse['price']);
    $outdata['place'] = $load->place($sourse['place']);
    $outdata['type'] = $load->type($sourse['type']);
    $outdata['status'] = $load->status($sourse['status']);
    $outdata['description'] = trim($sourse['description']);
    $outdata['start_time'] = $load->inttime($sourse['begin_date']);
    $outdata['end_time'] = $load->inttime($sourse['end_date']);
    $outdata['now_price'] = $load->price($sourse['nowprice']);
    $outdata['debtor'] = $load->debtor($sourse['debtor']);
    $outdata['organizer'] = $load->organizer($sourse['organizer'], $sourse['contact_person'], $sourse['manager'], $sourse['manager_card'], $sourse['inn']);
    $outdata['code'] = $load->nospaces($sourse['code']);
    $outdata['auct_link'] = trim($sourse['auct_link']);
    $outdata['case_number'] = $load->nospaces($sourse['case_number']);
    $outdata['lot_number'] = trim($sourse['lot_number']);
    $outdata['inn'] = trim($sourse['inn']);
    $outdata['item_key'] = $load->nospaces($sourse['key']);
    $outdata['platform_id'] = trim($sourse['platform_id']);
    $outdata['cat_id'] = $load->category($sourse['lotname'], $sourse['description']);
    $outdata['market_price'] = 0;
    $outdata['profit_rub'] = 0;
    $outdata['profit_proc'] = 0;
    $outdata['last_update'] = 0;
    $outdata['price_dif'] = $load->pricedif($outdata['price'], $outdata['now_price']);
    $outdata['debpoints'] = -1;
    $outdata['loadtime'] = time();

    //Забиваем id-шку
    self::$id = $outdata['item_key'];

    //print_r($outdata);

    //Собираем ошибки
    if(!$outdata['place'])
      $error[] = 'Can\'t detect place for '.$outdata['item_key'];
    if(!$outdata['type'])
      $error[] = 'Can\'t detect type for '.$outdata['item_key'];
    if(!$outdata['status'])
      $error[] = 'Can\'t detect status for '.$outdata['item_key'];
    if(!$outdata['platform_id'])
      $error[] = 'No platform for '.$outdata['item_key'];
    if(!$outdata['description'] AND !$outdata['name'])
      $error[] = 'No name and description for '.$outdata['item_key'];
    if(!$outdata['debtor'])
      $error[] = 'Not exists debtor for '.$outdata['item_key'];
    if(!preg_match('/^[0-9]+$/', $outdata['inn']))
      $error[] = 'Wrong inn for '.$outdata['item_key'];

    if(!$error)
    {
      //Сохраняем в базу через новую запись

      $out = array();
      foreach($outdata AS $key=>$value)
      {
        $out[] = ' `'.$key.'` = "'.core::$db->res($value).'" ';
      }

      if($out)
      {
        $part_query = implode(', ', $out);

        self::$error = '';
        return 'INSERT INTO `ds_maindata` SET '.$part_query.' ;';
      }
    }
    else
    {
      //Забиваем ошибки
      self::$error = implode(', ', $error);

      //Очищаем то что зря забили
      if($outdata['debtor'])
      {
        $cont_lots_deb = core::$db->query('SELECT COUNT(*) FROM `ds_maindata` WHERE `debtor` = "'.core::$db->res($outdata['debtor']).'" ;')->count();
        if(!$cont_lots_deb)
          core::$db->query('DELETE FROM `ds_maindata_debtors` WHERE `id` = "'.core::$db->res($outdata['debtor']).'" ;');
      }

      if($outdata['organizer'])
      {
        $cont_lots_organizer = core::$db->query('SELECT COUNT(*) FROM `ds_maindata` WHERE `organizer` = "'.core::$db->res($outdata['organizer']).'" ;')->count();
        if(!$cont_lots_organizer)
          core::$db->query('DELETE FROM `ds_maindata_organizers` WHERE `id` = "'.core::$db->res($outdata['organizer']).'" ;');
      }
    }
    return FALSE;
  }

  public static function get_id()
  {
    return self::$id;
  }

  public static function get_error()
  {
    return self::$error;
  }
}