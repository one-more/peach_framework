<?php

class ExceptionHandler {

    public static function initialize() {
        set_error_handler('peach_error_handler');

        set_exception_handler('peach_exception_handler');

        register_shutdown_function('peach_fatal_error_handler');
    }
}

function peach_exception_handler($exception) {
    error::log($exception->getMessage());
}

function peach_error_handler($errno, $errstr, $errfile, $errline) {

    $msg = "$errno : $errstr in $errline of $errfile";

    error::log($msg);
}

function peach_fatal_error_handler()
{
    if($arr = error_get_last()) {
        $msg = "FATAL ERROR : $arr[message] : $arr[line] : $arr[file] \r\n";

        $ds = DIRECTORY_SEPARATOR;

        file_put_contents(ROOT_PATH.$ds.'www'.$ds.'error.log', $msg, FILE_APPEND);
    }
}