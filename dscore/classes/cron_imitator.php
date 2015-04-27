<?php
defined('DS_ENGINE') or die('web_demon laughs');

class cron_imitator
{
  function __construct()
  {
     self::get_yield();
  }

  private static function get_yield()
  {
    $baze_yield = core::$set['baze_yield'];
    $res = core::$db->query('SELECT * FROM  `ds_maindata` WHERE `profit_proc` >= "'.$baze_yield.'" AND `mprice_update` > "'.(time()-(12*3600)).'";');
    $out = array();
    while($data = $res->fetch_array())
    {
      $out[] = '[url='.core::$home.'/card/'.$data['id'].']#'.$data['id'].'[/url] [color=#717171]('.$data['profit_proc'].'%)[/color]';
    }

    if($out)
    {
      $news = 'Высокая доходность у ' . (count($out) > 1 ? 'лотов' : 'лота') . ': ' . implode(', ', $out);
      pnews::add($news);
    }
  }
}