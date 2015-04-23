<?php
defined('DS_ENGINE') or die('web_demon laughs');

if(core::$rights < 100)
  denied();