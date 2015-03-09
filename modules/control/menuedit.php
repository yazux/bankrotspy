<?php
defined('DS_ENGINE') or die('web_demon laughs');
$error = array();
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_menu` WHERE `id` = "' . $id . '" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();

$sect = core::$db->query('SELECT * FROM `ds_menu` ORDER BY `sort`;');
$s_count = $sect->num_rows;
$all_ids = array();
$outs = array();
if($s_count > 1)
{
  while($ot = $sect->fetch_assoc())
  {
    if($ot['id'] != $id)
    {
      $arr = array();
      $all_ids[$ot['sort']] = $ot['id'];
      $arr['id'] = $ot['id'];
      $arr['name'] = $ot['name'];
      if(($ot['sort'] + 1) == $data['sort'])
        $arr['sel'] = 1;
      $outs[] = $arr;
    }
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

  if($after_id != 0)
    $last_pos = array_search($after_id, $all_ids);
  else
    $last_pos = 0;

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
    $to_req = '';
    $af_new = $last_pos + 1;
    if($s_count > 1)
    {
      if($af_new > $data['sort'])
      {
        foreach($all_ids AS $key => $value)
        {
          if($key > $data['sort'] AND $key < $af_new)
          {
            $to_req .= 'UPDATE `ds_menu` SET `sort` = "' . ($key - 1) . '" WHERE `id` = "' . $value . '";';
          }
        }
        if($to_req)
        {
          core::$db->multi_query($to_req);
          core::$db->multi_free();
        }
        core::$db->query('UPDATE `ds_menu` SET `sort` = "' . ($last_pos) . '" WHERE `id` = "' . $data['id'] . '";');
      }
      elseif($af_new < $data['sort'])
      {
        foreach($all_ids AS $key => $value)
        {
          if($key > $last_pos AND $key < $data['sort'])
          {
            $to_req .= 'UPDATE `ds_menu` SET `sort` = "' . ($key + 1) . '" WHERE `id` = "' . $value . '";';
          }
        }
        if($to_req)
        {
          core::$db->multi_query($to_req);
          core::$db->multi_free();
        }
        core::$db->query('UPDATE `ds_menu` SET `sort` = "' . ($last_pos + 1) . '" WHERE `id` = "' . $data['id'] . '";');
      }
    }

    if($name == $data['name'] AND $af_new == $data['order'] AND $link == $data['link'] AND $one_cnt == $data['one_counter'] AND $two_cnt == $data['two_counter'] AND $three_cnt == $data['three_counter'])
      $error[] = lang('no_changes');
    else
    {
      core::$db->query('UPDATE `ds_menu` SET
         `name` = "' . core::$db->res($name) . '",
         `link` = "' . core::$db->res(str_replace(core::$home, '<<home_link>>', $link)) . '",
         `one_counter` = "' . core::$db->res($one_cnt) . '",
         `two_counter` = "' . core::$db->res($two_cnt) . '",
         `three_counter` = "' . core::$db->res($three_cnt) . '"
         WHERE `id` = "' . $data['id'] . '";');

      func::notify(lang('edit_item'), lang('new_item_changed'), core::$home . '/control/menu', lang('continue'));
    }
  }
}

engine_head(lang('edit_item'));
temp::assign('id', $data['id']);
temp::HTMassign('cntall', counts::all());

temp::assign('one_cnt', $data['one_counter']);
temp::assign('two_cnt', $data['two_counter']);
temp::assign('three_cnt', $data['three_counter']);

if($data['sort'] == 1)
  temp::assign('its_top', 1);
temp::HTMassign('outs', $outs);
if($s_count > 1)
  temp::assign('other_sections', 1);
temp::HTMassign('name', $data['name']);
temp::HTMassign('link', str_replace('<<home_link>>', core::$home, $data['link']));
temp::HTMassign('error', $error);
temp::display('control.menuedit');
engine_fin();


