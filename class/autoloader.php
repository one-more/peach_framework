<?php

class Autoloader {

    public static function load_extension($name) {
        $extension_class = $name;
        $name   = strtolower($name);
        $extension_dir  = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = "{$extension_dir}.tar";
        $extension_path_gz = "{$extension_dir}.tar.gz";

        if(file_exists($extension_build_dir)) {
            if(static::is_extension_changed($name) || !file_exists($extension_path_gz)) {
                if(file_exists($extension_path_gz)) {
                    Phar::unlinkArchive($extension_path_gz);
                }
                $phar   = new PharData($extension_path);
                $phar->buildFromDirectory($extension_build_dir);
                $phar->compress(Phar::GZ);
                if(file_exists($extension_path)) {
                    unlink($extension_path);
                }
            }
        } else if(!file_exists($extension_path_gz)) {
            return false;
        }
        if(!class_exists($extension_class)) {
            require_once "phar://{$extension_path_gz}/{$name}.php";
        }
        return true;
    }

    private static function is_extension_changed($name) {
        $name   = strtolower($name);
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = ROOT_PATH.DS.'extensions'.DS.$name.".tar.gz";
        if(file_exists($extension_build_dir) && file_exists($extension_path)) {
            $dir_iterator   = new RecursiveDirectoryIterator($extension_build_dir);
            $itertaor   = new RecursiveIteratorIterator($dir_iterator);
            $itertaor->rewind();
            while($itertaor->valid()) {
                if(!$itertaor->isDot()) {
                    $file   = $extension_build_dir.DS.$itertaor->getSubPathName();
                    $phar_file  = "phar://{$extension_path}/".$itertaor->getSubPathName();
                    if(!file_exists($phar_file)) {
                        return true;
                    } else {
                        $build_file_hash   = md5(file_get_contents($file));
                        $phar_file_hash = md5(file_get_contents($phar_file));
                        if($build_file_hash != $phar_file_hash) {
                            return true;
                        }
                    }
                }
                $itertaor->next();
            }
            return false;
        } else {
            return false;
        }
    }

    public static function load_class($name, $dir = 'class') {
        $class_name = strtolower($name).'.php';
        $file   = ROOT_PATH.DS.$dir.DS.$class_name;
        if(!file_exists($file)) {
            return false;
        } else {
            require_once $file;
            return true;
        }
    }

    public static function load_trait($name) {
        return static::load_class($name, $dir = 'trait');
    }

    public static function load_template($name) {
        $name   = strtolower($name);
        $template   = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.$name.DS.$template;
        if(file_exists($file)) {
            require_once $file;
            return true;
        } else {
            return false;
        }
    }

    public static function load_interface($name) {
        return static::load_class($name, $dir = 'interface');
    }

    public static function load_exception($name) {
        return static::load_class($name, $dir = 'exception');
    }

    public static function load_helper($name) {
        return static::load_class($name, $dir = 'helper');
    }

    public static function init_autoload() {
        spl_autoload_register([__CLASS__,'load_class']);
        spl_autoload_register([__CLASS__,'load_extension']);
        spl_autoload_register([__CLASS__,'load_trait']);
        spl_autoload_register([__CLASS__,'load_template']);
        spl_autoload_register([__CLASS__,'load_interface']);
        spl_autoload_register([__CLASS__,'load_exception']);
        spl_autoload_register([__CLASS__,'load_helper']);
    }
}