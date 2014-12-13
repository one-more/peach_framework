<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';

spl_autoload_register(['Application','load_class']);
spl_autoload_register(['Application','load_extension']);
spl_autoload_register(['Application','load_trait']);
spl_autoload_register(['Application','load_template']);

Application::init_system();

$system = Application::get_class('System');
$system->initialize();

$template   = Application::get_class($system->get_template());
$template->route();