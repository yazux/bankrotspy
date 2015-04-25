<?php
defined('DS_ENGINE') or die('web_demon laughs');

$delid = abs(intval(POST('delid')));

core::$db->query('DELETE FROM `ds_maindata_bad_data` WHERE `id` = "'.core::$db->res($delid).'" ;');

echo 'ok';