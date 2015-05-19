<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once realpath(dirname(__DIR__.'../')).'/resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';

class TestsEnv {
	public static function initialize() {
		spl_autoload_register(['Application','load_class']);
		spl_autoload_register(['Application','load_extension']);
		spl_autoload_register(['Application','load_trait']);
		spl_autoload_register(['Application','load_template']);
		spl_autoload_register(['Application','load_interface']);
	}
}

TestsEnv::initialize();