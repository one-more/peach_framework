<?php

class Application {

    public static function load_extension($name) {
        $name   = strtolower($name);
        $extension_dir  = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = "{$extension_dir}.phar";
        $extension_path_gz = "{$extension_dir}.phar.gz";

        if(file_exists($extension_build_dir)) {
            if(file_exists($extension_path)) {
                unlink($extension_path);
            }
            if(file_exists($extension_path_gz)) {
                unlink($extension_path_gz);
            }
            ini_set('phar.readonly', 0);
            $phar   = new Phar($extension_path);
            $phar->buildFromDirectory($extension_build_dir);
            $phar->compress(Phar::GZ);
            unlink($extension_path);
        } else if(!file_exists($extension_path_gz)) {
            return false;
        }
        require_once "phar://{$extension_path_gz}/{$name}.php";
    }

    public static function load_class($name) {
        $class_name = strtolower($name).'.php';
        $file   = ROOT_PATH.DS.'class'.DS.$class_name;
        if(!file_exists($file)) {
            return false;
        }
        require_once $file;
    }

    public static function load_trait($name) {
        $trait_name = strtolower($name).'.php';
        $file   = ROOT_PATH.DS.'trait'.DS.$trait_name;
        if(!file_exists($file)) {
            return false;
        }
        require_once $file;
    }
}