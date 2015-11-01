<?php
defined('DS_ENGINE') or die('web_demon laughs');
$error = array();
$id = abs(intval(GET('id')));

if(!$id)
  denied();

$req = core::$db->query('SELECT * FROM `ds_maindata` WHERE `id` = "' . $id . '" LIMIT 1 ;');
if(!$req->num_rows)
  denied();

$item = $req->fetch_assoc();

$regions = core::$db->query('SELECT number, name FROM `ds_maindata_regions`');
$regions = $regions->fetch_all(MYSQLI_ASSOC);

$categories = core::$db->query('SELECT * FROM `ds_maindata_category`');
$categories = $categories->fetch_all(MYSQLI_ASSOC);

if(POST('submit'))
{
    $place = text::st(POST('place'));
    $category = intval(POST('category'));
    if(!$place) {
        $error[] = lang('no_place');
    }
    
    if(!$error) {
        core::$db->query('UPDATE `ds_maindata` SET `place` = "' . core::$db->res(text::st($place)) . '", `cat_id` = "'.core::$db->res($category).'" WHERE `id` = "' . $id . '";');
        
        func::notify(lang('edit_item'), lang('item_updated'), core::$home . '/control/edititem?id='.$id, lang('continue'));
    }
}



engine_head(lang('edit_item'));
temp::HTMassign('item', $item);
temp::HTMassign('categories', $categories);
temp::HTMassign('regions', $regions);

temp::HTMassign('error', $error);
temp::display('control.itemedit');
engine_fin();