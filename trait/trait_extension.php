<?php

trait trait_extension {
	use trait_configuration;

    private $path;

    private $lang_path;

    public function get_path() {
        $extension  = strtolower(__CLASS__);
        return "pfmextension://{$extension}";
    }

    public function get_lang_path() {
        return $this->get_path().DS.'lang'.DS.CURRENT_LANG;
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