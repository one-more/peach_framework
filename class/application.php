<?php

class Application {

    private static $instances    = [];

    public static function load_extension($name) {
        $name   = strtolower($name);
        $extension_dir  = ROOT_PATH.DS.'extensions'.DS.$name;
        $extension_build_dir  = ROOT_PATH.DS.'build'.DS.$name;
        $extension_path = "{$extension_dir}.tar";
        $extension_path_gz = "{$extension_dir}.tar.gz";

        if(file_exists($extension_build_dir)) {
            if(static::is_extension_changed($name) || !file_exists($extension_path_gz)) {
                if(file_exists($extension_path)) {
                    unlink($extension_path);
                }
                if(file_exists($extension_path_gz)) {
                    Phar::unlinkArchive($extension_path_gz);
                }
                $phar   = new PharData($extension_path);
                $phar->buildFromDirectory($extension_build_dir);
                $phar->compress(Phar::GZ);
                unlink($extension_path);
            }
        } else if(!file_exists($extension_path_gz)) {
            return false;
        }
        require_once "phar://{$extension_path_gz}/{$name}.php";
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

    public static function get_class($name, $params = array()) {
        if(class_exists($name)) {
			if(!isset(static::$instances[$name])) {
				$reflection = new ReflectionClass($name);
				static::$instances[$name]   = $reflection->newInstanceArgs($params);
			}
			return static::$instances[$name];
		} else {
			throw new InvalidArgumentException('class does not exists');
		}
    }

    private static function init_dirs() {
        $system_dirs    = [
            ROOT_PATH.DS.'extensions'
        ];
        foreach($system_dirs as $el) {
            if(!file_exists($el)) {
                mkdir($el);
                chmod($el, 0777);
            }
        }
    }

	public static function init_autoload() {
		spl_autoload_register(['Application','load_class']);
		spl_autoload_register(['Application','load_extension']);
		spl_autoload_register(['Application','load_trait']);
		spl_autoload_register(['Application','load_template']);
		spl_autoload_register(['Application','load_interface']);
		spl_autoload_register(['Application','load_exception']);
	}

	public static function initialize() {
		static::init_autoload();

		static::init_dirs();

		$system = Application::get_class('System');
		$system->initialize();

		$lang_obj = self::get_class('Language');
		$current_lang = $lang_obj->get_language();
		define('CURRENT_LANG', $current_lang);
	}

	public static function start() {
		$system = static::get_class('System');
		$port = $_SERVER['SERVER_PORT'];
		if(static::is_dev()) {
			$tools = Application::get_class('Tools');
			$tools->check_node_processes();
		}
		if($port == 8080 && static::is_dev()) {
			$tools = new Tools;
			$tools->route();
		} else {
			$template   = static::get_class($system->get_template());
			static::start_template($template);
		}
	}

	private static function start_template(Template $template) {
		$template->route();
	}

	public static function remove_dir($path) {
		$it = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
		$files = new RecursiveIteratorIterator($it,
					 RecursiveIteratorIterator::CHILD_FIRST);
		foreach($files as $file) {
			if ($file->isDir()) {
				rmdir($file->getRealPath());
			} else {
				unlink($file->getRealPath());
			}
		}
		rmdir($path);
	}

	public static function return_bytes($p_sFormatted) {
		$p_sFormatted = (string)$p_sFormatted;
		$aUnits = [
			'B'=>0,
			'KB'=>1,
			'MB'=>2,
			'GB'=>3,
			'TB'=>4,
			'PB'=>5,
			'EB'=>6,
			'ZB'=>7,
			'YB'=>8
		];
		$sUnit = strtoupper(trim(substr($p_sFormatted, -2)));
		if(intval($sUnit) !== 0) {
			$sUnit = 'B';
		}
		if(!in_array($sUnit, array_keys($aUnits))) {
			return false;
		}
		$iUnits = trim(substr($p_sFormatted, 0, strlen($p_sFormatted) - 2));
		if(!intval($iUnits) == $iUnits) {
			return false;
		}
		return $iUnits * pow(1024, $aUnits[$sUnit]);
	}

	public static function is_assoc_array($array) {
		if(!is_array($array)) {
			throw new InvalidArgumentException('argument is not array');
		}
	  	return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

	public static function is_dev() {
		return $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ||
			strpos($_SERVER['HTTP_HOST'], 'dev') !== -1;
	}
}