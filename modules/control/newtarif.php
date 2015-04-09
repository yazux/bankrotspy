<?php
defined('DS_ENGINE') or die('web_demon laughs');

$error = array();

if(POST('submit'))
{
  $name = text::st(POST('name'));
  if(!$name)
    $error[] = lang('no_name');
  elseif(mb_strlen($name) > 300)
    $error[] = lang('name_too_long');

  $price = abs(intval(POST('price')));
  if(!$price)
    $error[] = lang('no_price');

  $longtime = abs(intval(POST('longtime')));
  if(!$longtime)
    $error[] = lang('no_longtime');

  $def_rights = array(10, 11);
  $rights = abs(intval(POST('rights')));
  if(!$rights)
    $error[] = lang('no_rights');
  elseif(!in_array($rights, $def_rights))
    $error[] = lang('wrong_rights');

  $descr = POST('descr');
  if(!$descr)
    $error[] = lang('no_descr');
  elseif(mb_strlen($descr) > 1000)
    $error[] = lang('descr_too_long');

  if(!$error)
  {
    core::$db->query('INSERT INTO `ds_tariffs` SET
       `name` = "'.core::$db->res($name).'",
       `longtime` = "'.$longtime.'",
       `price` = "'.$price.'" ,
       `rights` = "'.$rights.'",
       `descr` = "'.core::$db->res($descr).'" ,
       `cache` = "'.core::$db->res(text::presave($descr)).'",
       `min_rights` = "0" ,
       `max_rights` = "100"
       ;');

    func::notify(lang('new_item'), lang('new_item_added'), core::$home . '/control/tset', lang('continue'));
  }
}

engine_head(lang('new_item'));
temp::HTMassign('error', $error);
temp::HTMassign('rights',user::get_rights());

if(isset($rights))
  temp::assign('user_rights', $rights);

if(isset($name))
  temp::HTMassign('name', $name);

if(isset($price))
  temp::assign('price', $price);

if(isset($longtime))
  temp::assign('longtime', $longtime);

if(isset($descr))
  temp::assign('descr', $descr);

temp::display('control.newtarif');
engine_fin();