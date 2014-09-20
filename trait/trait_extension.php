<?php

trait trait_extension {

    public static function load_extension_class($name) {
        $class_name = strtolower($name).'.php';
        $extension  = strtolower(__CLASS__).'.phar.gz';
        $file   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension.DS.'class'.DS.$class_name;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }
}