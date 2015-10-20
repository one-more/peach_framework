<?php

namespace common\classes;

require_once ROOT_PATH.DS.'common'.DS.'helpers'.DS.'stringhelper.php';

use common\helpers\StringHelper;

class AutoLoader {

    public static function load_extension($name) {
        $name = basename($name);
        if(!Application::extension_exists($name)) {
            return false;
        }

        $extension_class = $name;
        $name = strtolower($name);
        $extension_file_gz = ROOT_PATH.DS.'extensions'.DS."{$name}.tar.gz";
        $extension_build_dir = ROOT_PATH.DS.'build'.DS.$name;

        if(file_exists($extension_build_dir)) {
            if(self::is_extension_changed($name) || !self::is_extension_built($name)) {
                self::build_extension($name);
            }
        }

        if(!class_exists($extension_class, false)) {
            require_once "phar://{$extension_file_gz}/{$name}.php";
        }

        return true;
    }

    private static function build_extension($name) {
        $name = strtolower($name);
        $extension_file = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir = ROOT_PATH.DS.'build'.DS.$name;
        $extension_file_tar = "{$extension_file}.tar";
        $extension_file_gz = "{$extension_file}.tar.gz";
        if(file_exists($extension_file_gz)) {
            \Phar::unlinkArchive($extension_file_gz);
        }
        $phar = new \PharData($extension_file_tar);
        $phar->buildFromDirectory($extension_build_dir);
        $phar->compress(\Phar::GZ);
        if(file_exists($extension_file_tar)) {
            unlink($extension_file_tar);
        }
    }

    private static function is_extension_built($name) {
        $name = strtolower($name);
        $extension_file = ROOT_PATH.DS.'extensions'.DS."{$name}.tar.gz";
        return file_exists($extension_file);
    }

    private static function is_extension_changed($name) {
        $name = strtolower($name);
        $extension_build_dir = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = ROOT_PATH.DS.'extensions'.DS.$name.'.tar.gz';
        if(file_exists($extension_build_dir) && file_exists($extension_path)) {
            $dir_iterator = new \RecursiveDirectoryIterator($extension_build_dir);
            /**
             * @var $iterator \RecursiveDirectoryIterator
             */
            $iterator = new \RecursiveIteratorIterator($dir_iterator);
            $iterator->rewind();
            while($iterator->valid()) {
                if(!$iterator->isDot()) {
                    $file = $extension_build_dir.DS.$iterator->getSubPathName();
                    $phar_file  = "phar://{$extension_path}/".$iterator->getSubPathName();
                    if(!file_exists($phar_file)) {
                        return true;
                    } else {
                        $build_file_hash = md5(file_get_contents($file));
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

    public static function load_template($name) {
        $name = basename(strtolower($name));
        $file = ROOT_PATH.DS.'templates'.DS.$name.DS."{$name}.php";
        if(is_file($file)) {
            require_once $file;
            return true;
        } else {
            return false;
        }
    }

    public static function load_class($name) {
        $parts = explode('\\', $name);
        $class_name = strtolower(array_pop($parts)).'.php';
        $parts = array_map([StringHelper::class, 'camelcase_to_dash'], $parts);
        $parts = array_map('strtolower', $parts);
        $path = count($parts) ? implode(DS, $parts).DS.$class_name : $class_name;
        $file = ROOT_PATH.DS.$path;

        if(!file_exists($file)) {
            return false;
        } else {
            require_once $file;
            return true;
        }
    }

    public static function init_autoload() {
        spl_autoload_register([__CLASS__, 'load_class']);
        spl_autoload_register([__CLASS__, 'load_extension']);
        spl_autoload_register([__CLASS__, 'load_template']);
    }
}