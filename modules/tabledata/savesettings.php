<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!core::$user_id)
  exit('1');

//Настройки по умолчанию
$set_table_array = defaultset::get();

$jset = POST('jsettings');
$jset = json_decode($jset, 1);
if(!$jset OR !is_array($jset))
  exit('2');

$save_set = array();
foreach($set_table_array AS $key => $value)
{
  if(isset($jset[$key]) AND is_array($jset[$key]) AND is_array($value))
  {
    $new_arr = array();
    foreach($jset[$key] AS $k => $v)
    {
      $new_arr[intval(abs($k))] = $v ?  1 : 0;
    }
    $save_set[$key] = $new_arr;
  }
  elseif(isset($jset[$key]) AND !is_array($jset[$key]) AND !is_array($value))
    $save_set[$key] = $jset[$key];
  else
    $save_set[$key] = $value;
}

function create_new_profile($tosave)
{
  core::$db->query('INSERT INTO `ds_search_profiles` SET
       `userid`="'.core::$user_id.'",
       `profile`="'.core::$db->res($tosave).'",
       `pname`="'.core::$db->res(lang('def_profile')).'"
        ;');

  core::$user_set['tabledata'] = core::$db->insert_id;
  core::$db->query('UPDATE `ds_users` SET `settings`="'.core::$db->res(serialize(core::$user_set)).'" WHERE `id` = "'.core::$user_id.'";');

  return true;
}

$tosave = json_encode($save_set);

if(isset(core::$user_set['tabledata']) AND core::$user_set['tabledata'])
{
  //Если настройки уже есть, то обновляем текущие
  $res = core::$db->query('SELECT * FROM `ds_search_profiles` WHERE `id` = "'.core::$db->res(core::$user_set['tabledata']).'" AND `userid` = "'.core::$user_id.'";');
  if($res->num_rows)
    core::$db->query('UPDATE `ds_search_profiles` SET `profile`="'.core::$db->res($tosave).'" WHERE `id` = "'.core::$db->res(core::$user_set['tabledata']).'";');
  else
    create_new_profile($tosave);
}
else
  create_new_profile($tosave);
