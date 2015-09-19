<?php

class Autoloader {

    public static function load_extension($name) {
        if(!Application::extension_exists($name)) {
            return false;
        }

        $extension_class = $name;
        $name = strtolower($name);
        $extension_file = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir = ROOT_PATH.DS.'build'.DS.$name;
        $extension_file_tar = "{$extension_file}.tar";
        $extension_file_gz = "{$extension_file}.tar.gz";

        /*
         * build extension if it has changed or has not built yet
         */
        if(file_exists($extension_build_dir)) {
            if(self::is_extension_changed($name) || !file_exists($extension_file_gz)) {
                if(file_exists($extension_file_gz)) {
                    Phar::unlinkArchive($extension_file_gz);
                }
                $phar = new PharData($extension_file_tar);
                $phar->buildFromDirectory($extension_build_dir);
                $phar->compress(Phar::GZ);
                if(file_exists($extension_file_tar)) {
                    unlink($extension_file_tar);
                }
            }
        }

        if(!class_exists($extension_class)) {
            require_once "phar://{$extension_file_gz}/{$name}.php";
        }

        return true;
    }

    private static function is_extension_changed($name) {
        $name   = strtolower($name);
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = ROOT_PATH.DS.'extensions'.DS.$name.'.tar.gz';
        if(file_exists($extension_build_dir) && file_exists($extension_path)) {
            $dir_iterator = new RecursiveDirectoryIterator($extension_build_dir);
            /**
             * @var $iterator RecursiveDirectoryIterator
             */
            $iterator = new RecursiveIteratorIterator($dir_iterator);
            $iterator->rewind();
            while($iterator->valid()) {
                if(!$iterator->isDot()) {
                    $file = $extension_build_dir.DS.$iterator->getSubPathName();
                    $phar_file  = "phar://{$extension_path}/".$iterator->getSubPathName();
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
                $iterator->next();
            }
            return false;
        } else {
            return false;
        }
    }

    public static function load_class($name, $dir = 'class') {
        $class_name = strtolower($name).'.php';
        $file = ROOT_PATH.DS.$dir.DS.$class_name;
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

    public static function load_controller($name) {
        return static::load_class($name, $dir = 'controller');
    }

    public static function load_model($name) {
		return static::load_class($name, $dir = 'model');
    }

    public static function init_autoload() {
        spl_autoload_register([__CLASS__,'load_class']);
        spl_autoload_register([__CLASS__,'load_extension']);
        spl_autoload_register([__CLASS__,'load_trait']);
        spl_autoload_register([__CLASS__,'load_template']);
        spl_autoload_register([__CLASS__,'load_interface']);
        spl_autoload_register([__CLASS__,'load_exception']);
        spl_autoload_register([__CLASS__,'load_model']);
        spl_autoload_register([__CLASS__,'load_controller']);
        spl_autoload_register([__CLASS__,'load_helper']);
    }
}