<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(!in_array(core::$rights, [10, 11, 100]))
  exit('access denied');

$item = abs(intval(POST('itemid')));
$action = abs(intval(POST('actionid')));

if(!$item)
  exit('nothing to do with');

$res_art = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "'.$item.'" LIMIT 1;');
if(!$res_art->num_rows)
  exit('item no exists');

$res = core::$db->query('SELECT * FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
if($res->num_rows)
  $item_added = true;
else
  $item_added = false;

if(!$action)
{
  //добавляем в избранное
  if($item_added)
    exit('item already in favorites');
  core::$db->query('INSERT INTO `ds_maindata_favorive` SET `item` = "'.$item.'", `user_id` = "'.core::$user_id.'", `favtime` = "'.time().'";');
}
else
{
  //Удаляем из избранного
    if(!$item_added)
        exit('item already not in favorites');
    
    core::$db->query('DELETE FROM `ds_maindata_favorive` WHERE `item` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
    core::$db->query('DELETE FROM `lot_notes` WHERE `lot_id` = "'.$item.'" AND `user_id` = "'.core::$user_id.'" LIMIT 1;');
}

echo 'ok';