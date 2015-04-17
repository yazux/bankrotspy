<?php
defined('DS_ENGINE') or die('web_demon laughs');

$dataarr = array('');

//Пока имитируем данные
$getdata = '1;Имущество ООО "Альфа-Ойл";763 907руб.; Арбитражный суд Республики тАтарстан  ;Публичное предложение;Объявлены торги ;Резервуар РГО 100-4 в количестве 2 ед., масло камазовское М10Г2к (2*216,5 л, 2007 г.в.), масло нигрол Тэп-15 (4*216,5 л, 2008 г.в.), масло индустриальное И-20А (3*216,5 л, 2007 г.в.), масло турбодизельное М10ДМ (1*216,5 л, 2007 г.в.), смазка пластичная Литол-24 (бар. 18 кг, 2008 г.в.). ;27.05.2015 09:00;06.03.2016 16:00;763907; ООО "Альфа-Ойл" ;;Общество с ограниченной ответственностью "Юридический центр "Право";   Общество с ограниченной ответственностью "Юридический центр "Право"  190000, г.Санкт-Петербург, переулок Гривцова, д. 5  420066, г. Казань, ул. Солдатская, 8, офис 304Б.  main@auction-house.ru">main@auction-house.ru  lenarkh@bk.ru">lenarkh@bk.ru  5710001  518-73-94, факс: 518-73-94 ; Цапурин  Сергей  Анатольевич ;;РАД-65175;https://bankruptcy.lot-online.ru/e-auction/auctionLotProperty.xhtml?parm=organizerUnid%3D1%3BlotUnid%3D960000078836%3Bmode%3Djust;А65-32506/2012 ;1; 1660035793 ;РАД-65175-lot-online;17';
$getarr = explode(';', $getdata);
$dataarr = array_merge($dataarr, $getarr);

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
$sourse['deblor']          = $dataarr[11];
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
$outdata['debtor'] = '';
$outdata['debtor_card'] = '';
$outdata['organizer'] = '';
$outdata['contact_person'] = '';
$outdata['manager'] = '';
$outdata['man_card'] = '';
$outdata['code'] = trim($sourse['code']);
$outdata['auct_link'] = trim($sourse['auct_link']);
$outdata['case_number'] = $load->nospaces($sourse['case_number']);
$outdata['lot_number'] = trim($sourse['lot_number']);
$outdata['inn'] = trim($sourse['inn']);
$outdata['item_key'] = $load->nospaces($sourse['key']);
$outdata['platform_id'] = trim($sourse['platform_id']);
$outdata['cat_id'] = '';
$outdata['market_price'] = 0;
$outdata['profit_rub'] = 0;
$outdata['profit_proc'] = 0;
$outdata['last_update'] = time();
$outdata['price_dif'] = $load->pricedif($outdata['price'], $outdata['now_price']);
$outdata['debpoints'] = 0;


//print_r($outdata);

//Собираем ошибки
if(!$outdata['place'])
  $error[] = 'Can\'t detect place for '.$sourse['key'];
if(!$outdata['type'])
  $error[] = 'Can\'t detect type for '.$sourse['key'];
if(!$outdata['status'])
  $error[] = 'Can\'t detect status for '.$sourse['key'];

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

    //core::$db->query('INSERT INTO `ds_maindata` SET '.$part_query.' ;');
    echo $part_query;
    echo 'ok';
  }
}
else
{
  //Выводим ошибки
  echo implode("\n", $error);
}

