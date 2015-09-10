<?php
spl_autoload_register(function($class) {
    $parts = array_slice(explode('\\', $class), 1);
    $file = ROOT_PATH.DS.'lib'.DS.'Validator'.DS.implode(DS, $parts).'.php';
    if(file_exists($file)) {
        require_once $file;
    }
    return file_exists($file);
});