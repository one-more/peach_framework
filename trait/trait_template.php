<?php

trait trait_template {
    public function __get($name) {
        switch($name) {
            case 'path':
                if(empty($this->path)) {
                    $this->path = ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__);
                }
                break;
        }
        return $this->$name;
    }

    public static function load_template_class($name, $dir = 'class') {
        if(strpos($name, '\\')) {
            $parts = explode('\\', $name);
            $name = array_pop($parts);
            $parts = array_map(['Application','camelcase_to_dash'], $parts);
            $name = implode(DS, $parts).DS.strtolower($name);
        } else {
            $name   = strtolower($name);
        }
        $class  = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__).DS.$dir.DS.$class;
        if(file_exists($file)) {
            require_once $file;
			return true;
        } else {
            return false;
        }
    }

    public static function load_template_controller($name) {
        return static::load_template_class($name, $dir = 'controller');
    }

    public static function load_template_model($name) {
		return static::load_template_class($name, $dir = 'model');
    }

	public static function load_template_view($name) {
		return static::load_template_class($name, $dir = 'view');
	}

	public static function load_template_trait($name) {
		return static::load_template_class($name, $dir = 'trait');
	}

	protected function register_autoload() {
		spl_autoload_register([__CLASS__, 'load_template_class']);
		spl_autoload_register([__CLASS__, 'load_template_model']);
		spl_autoload_register([__CLASS__, 'load_template_controller']);
		spl_autoload_register([__CLASS__, 'load_template_view']);
		spl_autoload_register([__CLASS__, 'load_template_trait']);
	}
}