<?php

if(!defined('ROOT_PATH')) {
	define('ROOT_PATH', realpath(dirname(__DIR__)));
}
if(!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('WEB_ROOT')) {
    define('WEB_ROOT', ROOT_PATH.DS.'www');
}
if(!defined('SMARTY_DIR')) {
	define('SMARTY_DIR', ROOT_PATH.DS.'lib'.DS.'Smarty'.DS);
}