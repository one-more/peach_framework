<?php

require_once realpath(dirname(__FILE__)).'/../../resource/defines.php';
require_once ROOT_PATH.DS.'common'.DS.'classes'.DS.'autoloader.php';

defined('STDIN') or define('STDIN', fopen('php://stdin', 'r'));
defined('STDOUT') or define('STDOUT', fopen('php://stdout', 'w'));
defined('STDERR') or define('STDERR', fopen('php://stderr', 'w'));