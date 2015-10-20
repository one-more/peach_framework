<?php

namespace System\handler;

use common\classes\Application;
use common\classes\Error;

class ExceptionHandler {

    public static function initialize() {
        set_error_handler('System\handler\peach_error_handler');

        set_exception_handler('System\handler\peach_exception_handler');

        register_shutdown_function('System\handler\peach_fatal_error_handler');
    }

    public static function show_error($message) {
        /**
         * @var $system \System
         */
        $system = Application::get_class(\System::class);
		$smarty = new \Smarty();
		$smarty->setTemplateDir($system->get_path().DS.'templates');
		$smarty->setCompileDir($system->get_path().DS.'templates_c');
        if($system->get_configuration()['show_errors']) {
            $error_class = $system->get_configuration()['error_block_class'];
            $params = [
                'class' => $error_class,
                'message'   => $message
            ];
            $smarty->assign($params);
            echo $smarty->getTemplate('message.tpl.html');
        }
    }
}

function peach_exception_handler($exception) {
    /**
     * @var $exception \Exception
     */
    $message = $exception->getMessage();
    Error::log($message);

    ExceptionHandler::show_error('an exception occurred');

	return false;
}

function peach_error_handler($errno, $errstr, $errfile, $errline) {
    $msg = "$errno : $errstr in $errline of $errfile";

    Error::log($msg);

    ExceptionHandler::show_error('an error occurred');

	return false;
}

function peach_fatal_error_handler() {
    if($arr = error_get_last()) {
        $msg = "FATAL ERROR : $arr[message] : $arr[line] : $arr[file] \r\n";

        $ds = DIRECTORY_SEPARATOR;

        file_put_contents(ROOT_PATH.$ds.'www'.$ds.'error.log', $msg, FILE_APPEND);
    }

	return false;
}