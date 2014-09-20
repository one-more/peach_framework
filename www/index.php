<?php
require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';

spl_autoload_register(['Application','load_class']);
spl_autoload_register(['Application','load_extension']);
spl_autoload_register(['Application','load_trait']);

$system = new System();
$system->initialize();