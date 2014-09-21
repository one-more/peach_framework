<?php
require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';

spl_autoload_register(['Application','load_class']);
spl_autoload_register(['Application','load_extension']);
spl_autoload_register(['Application','load_trait']);
spl_autoload_register(['Application','load_template']);

$system = Application::get_class('System');
$system->initialize();

$template   = Application::get_class($system->get_template());
$template->route();