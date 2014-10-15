<?php

class Application {

    static $instances    = [];

    public static function load_extension($name) {
        $name   = strtolower($name);
        $extension_dir  = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = "{$extension_dir}.tar";
        $extension_path_gz = "{$extension_dir}.tar.gz";

        if(file_exists($extension_build_dir) && static::is_extension_changed($name)) {
            if(file_exists($extension_path)) {
                unlink($extension_path);
            }
            if(file_exists($extension_path_gz)) {
                unlink($extension_path_gz);
            }
            $phar   = new PharData($extension_path);
            $phar->buildFromDirectory($extension_build_dir);
            $phar->compress(Phar::GZ);
            unlink($extension_path);
        } else if(!file_exists($extension_path_gz)) {
            return false;
        }
        require_once "phar://{$extension_path_gz}/{$name}.php";
    }

    protected static function is_extension_changed($name) {
        $name   = strtolower($name);
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = ROOT_PATH.DS.'extensions'.DS.$name.".tar.gz";
        if(!file_exists($extension_build_dir) || !file_exists($extension_path)) {
            $dir_iterator   = new RecursiveDirectoryIterator($extension_build_dir);
            $itertaor   = new RecursiveIteratorIterator($dir_iterator);
            $itertaor->rewind();
            while($itertaor->valid()) {
                if(!$itertaor->isDot()) {
                    $file   = $extension_build_dir.DS.$itertaor->getSubPathName();
                    $phar_file  = "phar://{$extension_path}/{$file}";
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

    public static function load_template($name) {
        $name   = strtolower($name);
        $template   = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.$name.DS.$template;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }

    public static function get_class($name, $params = array()) {
        if(!isset(static::$instances[$name])) {
            $reflection = new ReflectionClass($name);
            static::$instances[$name]   = $reflection->newInstanceArgs($params);
        }
        return static::$instances[$name];
    }

    public static function init_system() {
        $system_dirs    = [
            ROOT_PATH.DS.'extensions'
        ];
        foreach($system_dirs as $el) {
            if(!file_exists($el)) {
                mkdir($el);
                chmod($el, 0777);
            }
        }

        $system = static::get_class('System');
        $dump_file  = ROOT_PATH.DS.'resource'.DS.'dump_db.sql';
        if($system->get_configuration()['dump_db'] && !file_exists($dump_file)) {
            file_put_contents($dump_file, '');
            chmod($dump_file, 0777);
        }

        $db_dump_file   = ROOT_PATH.DS.'resource'.DS.'dump_db.sql';
        if(!file_exists($db_dump_file)) {
            file_put_contents($db_dump_file, '');
            chmod($db_dump_file, 0777);
        }
    }
}