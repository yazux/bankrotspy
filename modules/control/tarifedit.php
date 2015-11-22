<?php
defined('DS_ENGINE') or die('web_demon laughs');
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_tariffs` WHERE `id` = "' . $id . '" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$data = $req->fetch_assoc();

$error = array();

if(POST('submit'))
{
  $name = text::st(POST('name'));
  if(!$name)
    $error[] = lang('no_name');
  elseif(mb_strlen($name) > 300)
    $error[] = lang('name_too_long');

  $price = abs(intval(POST('price')));
  /*if(!$price)
    $error[] = lang('no_price');*/

  $longtime = abs(intval(POST('longtime')));
  if(!$longtime)
    $error[] = lang('no_longtime');

    $typetime = intval(POST('typetime'));

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
    core::$db->query('UPDATE `ds_tariffs` SET
       `name` = "'.core::$db->res($name).'",
       `longtime` = "'.$longtime.'",
       `typetime` = "'.$typetime.'",
       `price` = "'.$price.'" ,
       `rights` = "'.$rights.'",
       `descr` = "'.core::$db->res($descr).'" ,
       `cache` = "'.core::$db->res(text::presave($descr)).'",
       `min_rights` = "0" ,
       `max_rights` = "100"
       WHERE `id` = "'.$id.'";');

    func::notify(lang('new_item'), lang('new_item_added'), core::$home . '/control/tset', lang('continue'));
  }
}

engine_head(lang('new_item'));
temp::HTMassign('error', $error);
temp::assign('id', $data['id']);
temp::HTMassign('rights',user::get_rights());

if(isset($rights))
  temp::assign('user_rights', $rights);
else
  temp::assign('user_rights', $data['rights']);

if(isset($name))
  temp::HTMassign('name', $name);
else
  temp::assign('name', $data['name']);

if(isset($price))
  temp::assign('price', $price);
else
  temp::assign('price', $data['price']);

if(isset($typetime))
  temp::assign('typetime', $typetime);
else
  temp::assign('typetime', $data['typetime']);

if(isset($longtime))
  temp::assign('longtime', $longtime);
else
  temp::assign('longtime', $data['longtime']);

if(isset($descr))
  temp::assign('descr', $descr);
else
  temp::assign('descr', $data['descr']);

temp::display('control.tarifedit');
engine_fin();