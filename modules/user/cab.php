<?php
defined('DS_ENGINE') or die('web_demon laughs');
if(core::$user_id)
{
  $res = core::$db->query('SELECT * FROM `ds_users` WHERE `id` = "'.core::$user_id.'" LIMIT 1');

  $data = $res->fetch_array();
  $user_info = unserialize($data['info']);
  $user_set = unserialize($data['settings']);

  $eng_right = user::get_rights();
  $avatar = user::get_avatar(core::$user_id, $data['avtime'], 1);

  if($data['ordercode'])
  {
    $tar_tariff = array();
    $res = core::$db->query('SELECT * FROM `ds_tariffs` ORDER BY `price` ASC;');
    if($res->num_rows)
    {
      while($dat = $res->fetch_array())
      {
        $tar_tariff[$dat['id']] = $dat['name'];
      }
      $ord_arr = explode('-', $data['ordercode']);
      $ord_arr[1] = abs(intval($ord_arr[1]));
      $ord_arr[2] = abs(intval($ord_arr[2]));
      $ord_arr[3] = abs(intval($ord_arr[3]));

      $tariff = $tar_tariff[$ord_arr[2]];
    }
    else
      $tariff = lang('uncknown_tarif');
  }

  if($data['desttime'])
    $desttime = ds_time($data['desttime']);

  engine_head(lang('cab'));
  temp::assign('user_ip', core::$ip);
  temp::assign('user_ua', core::$ua);
  temp::assign('avatar', $avatar);
  if(isset($tariff))
    temp::assign('tariff', $tariff);

  temp::assign('ordercode', $data['ordercode']);
  if(isset($desttime))
    temp::assign('desttime', $desttime);
  temp::assign('rights',$eng_right[$data['rights']]);
  temp::display('user.cab');
  engine_fin();   
}
else
  denied();



