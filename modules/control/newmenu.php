<?php
defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

$sect = core::$db->query('SELECT * FROM `ds_menu` ORDER BY `sort`;');
$s_count = $sect->num_rows;
$all_ids = array();
$outs = array();
if($s_count)
{
  while($data = $sect->fetch_assoc())
  {
    $arr = array();
    $all_ids[$data['sort']] = $data['id'];
    $arr['id'] = $data['id'];
    $arr['name'] = $data['name'];
    $outs[] = $arr;
  }
}

if(POST('submit'))
{
  $link = text::st(POST('pruf_link'));
  $name = text::st(POST('link_name'));
  $after_id = intval(abs(POST('link_sort')));
  if(!$name)
    $error[] = lang('no_name');
  elseif(mb_strlen($name) > 200)
    $error[] = lang('name_too_long');

  if(!$link)
    $error[] = lang('no_link');

  if(!in_array($after_id,$all_ids))
    $after_id = 0;

  $allcnt = counts::all();
  $one_cnt = POST('one_cnt');
  if(!isset($allcnt[$one_cnt]))
    $one_cnt = '';


  $two_cnt = POST('two_cnt');
  if(!isset($allcnt[$two_cnt]))
    $two_cnt = '';

  $three_cnt = POST('three_cnt');
  if(!isset($allcnt[$three_cnt]))
    $three_cnt = '';

  if(!$error)
  {
    $link = str_replace(core::$home, '<<home_link>>', $link);
    if(!$s_count)
      core::$db->query('INSERT INTO `ds_menu` SET `sort` = "1", `name` = "'.core::$db->res($name).'", `link` = "'.core::$db->res($link).'";');
    else
    {
      if($after_id != 0)
        $last_pos =  array_search($after_id,$all_ids);
      else
        $last_pos = 0;

      $tosql = '';
      foreach($all_ids AS $key=>$value)
      {
        if($key > $last_pos)
          $tosql .=  'UPDATE `ds_menu` SET `sort` = "'.($key+1).'" WHERE `id` = "'.$value.'";';
      }

      if($tosql)
      {
        core::$db->multi_query($tosql);
        core::$db->multi_free();
      }
      core::$db->query('INSERT INTO `ds_menu` SET `sort` = "'.($last_pos+1).'", `name` = "'.core::$db->res($name).'", `one_counter` = "'.core::$db->res($one_cnt).'", `two_counter` = "'.core::$db->res($two_cnt).'", `three_counter` = "'.core::$db->res($three_cnt).'", `link` = "'.core::$db->res($link).'";');
    }

    func::notify(lang('new_item'), lang('new_item_added'), core::$home . '/control/menu', lang('continue'));
  }
}

engine_head(lang('new_item'));
temp::HTMassign('error', $error);
if(isset($link))
  temp::assign('link', $link);
if(isset($name))
  temp::assign('name', $name);

if(isset($one_cnt))
  temp::assign('one_cnt', $one_cnt);
if(isset($two_cnt))
  temp::assign('two_cnt', $two_cnt);
if(isset($three_cnt))
  temp::assign('three_cnt', $three_cnt);

temp::HTMassign('cntall', counts::all());
temp::HTMassign('outs', $outs);
if($s_count > 0)
  temp::assign('other_sections', 1);
temp::display('control.newmenu');
engine_fin();