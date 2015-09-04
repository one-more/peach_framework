<?php

trait trait_extension {
	use trait_configuration;

    public function __get($name) {
        switch($name) {
            case 'path':
                if(empty($this->path)) {
                    $extension  = strtolower(__CLASS__).'.tar.gz';
                    $this->path = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension;
                }
                break;
            case 'lang_path':
                if(empty($this->lang_path)) {
                    $this->lang_path = $this->path.DS.'lang'.DS.CURRENT_LANG;
                }
                break;
        }
        return $this->$name;
    }

    public static function load_extension_class($name, $dir = 'class') {
        $class_name = strtolower($name).'.php';
        $extension  = strtolower(__CLASS__).'.tar.gz';
        $file   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension.DS.$dir.DS.$class_name;
		if(file_exists($file)) {
            require_once $file;
			return true;
        } else {
            return false;
        }
    }

    public static function load_extension_model($name) {
        return static::load_extension_class($name, $dir = 'model');
    }

    public static function load_extension_controller($name) {
        return static::load_extension_class($name, $dir = 'controller');
    }

	public static function load_extension_view($name) {
		 return static::load_extension_class($name, $dir = 'view');
	}

	protected function register_autoload() {
		spl_autoload_register([__CLASS__, 'load_extension_class']);
        spl_autoload_register([__CLASS__, 'load_extension_model']);
        spl_autoload_register([__CLASS__, 'load_extension_controller']);
        spl_autoload_register([__CLASS__, 'load_extension_view']);
	}
}