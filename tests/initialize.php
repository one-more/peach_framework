<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once realpath(dirname(__DIR__.'../')).'/resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';

class TestsEnv {
	public static function initialize() {
		Application::init_autoload();
	}
}

TestsEnv::initialize();