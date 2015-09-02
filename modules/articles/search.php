<?php
defined('DS_ENGINE') or die('web_demon laughs');

$item = GET('q');
$symbols_area = 200;

$out = array();
if($item)
{
  new nav; //Постраничная навигация

  $total = core::$db->query('SELECT COUNT(*) FROM `ds_article` WHERE MATCH (`name`, `text`) AGAINST ("' . core::$db->res($item) . '" IN BOOLEAN MODE) AND `ds_article`.`type` = "0" ;')->count();
  //Запросы спер у Кенига из его либы для джона, ибо я такая ленивая скотина что даже гуглить поленился
  $res = core::$db->query('
    SELECT `ds_article`.*, `ds_users`.`lastvisit`, `ds_users`.`avtime`, `ds_users`.`sex`, `ds_users`.`rights`,
    MATCH (`ds_article`.`name`, `ds_article`.`text`) AGAINST ("' . core::$db->res($item) . '" IN BOOLEAN MODE) as `rel`
    FROM `ds_article` LEFT JOIN `ds_users` ON `ds_article`.`userid` = `ds_users`.`id`
    WHERE MATCH (`ds_article`.`name`, `ds_article`.`text`) AGAINST ("' . core::$db->res($item) . '" IN BOOLEAN MODE)
    AND `ds_article`.`type` = "0"
    ORDER BY `rel` DESC
    LIMIT ' . nav::$start . ', ' . nav::$kmess . ';
  ');

  $item_arr = explode(' ', $item);
  $sql = '';

  $out2 = array();
  while($data = $res->fetch_assoc())
  {
    $arr = array();

    $pos = '';
    $text = $data['text'];
    foreach($item_arr AS $val)
    {
      $val = trim($val);
      $pos = 0;
      if($val)
      {
        //Это какой-то дурдом. Работает и хер с ним.
        preg_match('/' . preg_quote($val) . '/ui', $text, $matches, PREG_OFFSET_CAPTURE);
        if($matches[0][0])
        {
          $pos = mb_strpos($text, $matches[0][0]);
          break;
        }
      }
    }
    $text = text::cut($text);
    $start = $pos + 1 - $symbols_area;
    if($start < 0)
      $start = 0;
    $lenght = $symbols_area * 2;
    $text = mb_substr($text, $start, $lenght);

    $arr['text'] = '...' . replace_finded($text, $item_arr) . '...';


    $arr['name'] = replace_finded(text::st($data['name']), $item_arr);
    $arr['avatar'] = user::get_avatar($data['userid'], $data['avtime'], 1);
    $arr['id'] = $data['id'];
    $arr['userid'] = $data['userid'];
    $arr['user'] = $data['autor'];
    $arr['rating'] = ($data['rating_plus'] - $data['rating_minus']);
    $arr['time'] = ds_time($data['time']);

    $sql .= 'SELECT COUNT(*) FROM `ds_comm` WHERE `module`="articles" AND `mid` = "' . $data['id'] . '" ;';

    $keys = array();
    $keys_data = array();
    $keys_data = unserialize($data['keywords']);
    if($keys_data)
    {
      foreach($keys_data AS $kid)
      {
        if(isset($art_keys[$kid]))
          $keys[$kid] = $art_keys[$kid];
      }
    }
    $arr['keys'] = $keys;
    $out[] = $arr;
  }

  if($sql)
    core::$db->multi_query($sql);

  foreach($out AS $key => $value)
  {
    $arr = array();
    $arr = $value;

    //Количество комментариев к статье
    core::$db->next_result();
    $res = core::$db->store_result()->fetch_row();
    $arr['comm_count'] = $res[0];

    $out2[] = $arr;
  }
}

function replace_finded($text, $words_arr)
{
  foreach($words_arr AS $val)
  {
    $val = text::st($val);
    $text = preg_replace('/' . preg_quote($val) . '/ui', '<span class="search_highlight">' . $val . '</span>', $text);
  }
  return $text;
}

core::$db->multi_free();
$out = $out2;

engine_head(lang('search_stat'));
temp::HTMassign('out', $out);
temp::assign('item', $item);
if(CAN('stats_create', 0) OR CAN('add_stats_moderate', 0))
  temp::assign('can_cr_stat', 1);

if($item)
{
  temp::assign('item_exists', 1);
  temp::HTMassign('navigation', nav::display($total, core::$home . '/articles/search?q=' . rawurlencode($item) . '&amp;'));
}
temp::display('articles.search');
engine_fin();