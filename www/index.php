<?php

require_once realpath(dirname(__DIR__.'../')).'/resource/defines.php';
require_once ROOT_PATH.DS.'common'.DS.'classes'.DS.'application.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';
require_once ROOT_PATH.DS.'lib'.DS.'vendor'.DS.'autoload.php';

\common\classes\Application::initialize();
\common\classes\Application::start();