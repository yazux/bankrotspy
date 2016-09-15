<?php

defined('DS_ENGINE') or die('web_demon laughs');

$access = true;

if(!core::$user_id) {
    $access = '<i class="fa fa-lock" onmouseout="toolTip()" onmouseover="toolTip(\'Информация доступна для зарегистрированных пользователей\')"></i>';
} elseif (!CAN('rating_arbitration')) {
    $access = '<i class="fa fa-lock" onmouseover="toolTip(\'Информация доступна на подписке VIP\')"></i>';
}