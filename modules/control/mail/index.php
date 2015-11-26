<?php

defined('DS_ENGINE') or die('access denied');


engine_head(lang('index_title'));
temp::HTMassign('rmenu', $out3);
temp::display('control/mail/index');
engine_fin();