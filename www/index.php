<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';

Application::initialize();
Application::start();