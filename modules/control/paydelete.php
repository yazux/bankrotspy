<?php
defined('DS_ENGINE') or die('web_demon laughs');

$id = abs(intval(GET('id')));
if( !$id ) {
    exit("Не задан ID!");
}

$t = core::$db->query( "SELECT * FROM `ds_paid` WHERE `id` = " . $id );
if( $t->num_rows > 0 ) {
    core::$db->query( "DELETE FROM `ds_paid` WHERE `id` = " . $id );
    exit("ok");
} else {
    exit("Нет такой транзакции");
}
