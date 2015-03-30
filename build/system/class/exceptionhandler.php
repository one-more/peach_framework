<?php

class ExceptionHandler {

    public static function initialize() {
        set_error_handler('peach_error_handler');

        set_exception_handler('peach_exception_handler');

        register_shutdown_function('peach_fatal_error_handler');
    }

    public static function show_error($message) {
        $system   = Application::get_class('System');
		$template   = Application::get_class($system->get_template());
		$smarty = new Smarty();
		$smarty->setTemplateDir($system->path.DS.'templates');
		$smarty->setCompileDir($template->path.DS.'templates_c');
        if($system->get_configuration()['show_errors']) {
            $error_class    = $system->get_configuration()['error_block_class'];
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
    $message    = $exception->getMessage();
    error::log($message);

    ExceptionHandler::show_error('an exception occurred');

	return false;
}

function peach_error_handler($errno, $errstr, $errfile, $errline) {
    $msg = "$errno : $errstr in $errline of $errfile";

    error::log($msg);

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