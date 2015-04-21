<?php

trait trait_extension {
	use trait_json;

    public function __get($name) {
        switch($name) {
            case 'path':
                if(empty($this->path)) {
                    $extension  = strtolower(__CLASS__).'.tar.gz';
                    $this->path = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension;
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

    protected function get_params($name = null) {
        if(!$name) {
            $name   = strtolower(__CLASS__);
        }
        $path   = ROOT_PATH.DS.'resource'.DS."{$name}.json";
        if(file_exists($path)) {
            return json_decode(file_get_contents($path), true);
        } else {
            return [];
        }
    }

    protected function set_params($name, $params) {
        $old_params = $this->get_params($name);
        $new_params = array_merge($old_params, $params);
        $params_str = $this->array_to_json_string($new_params);
        file_put_contents(ROOT_PATH.DS.'resource'.DS."{$name}.json", $params_str);
    }

    protected function unset_param($param, $name = null) {
        if(!$name) {
            $name   = strtolower(__CLASS__);
        }
        $params = $this->get_params($name);
        unset($params[$param]);
        $params_str = $this->array_to_json_string($params);
        file_put_contents(ROOT_PATH.DS.'resource'.DS."{$name}.json", $params_str);
    }

    protected function get_model($name) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class($name, $params);
        return $model;
    }

	protected function register_autoload() {
		spl_autoload_register([__CLASS__, 'load_extension_class']);
        spl_autoload_register([__CLASS__, 'load_extension_model']);
        spl_autoload_register([__CLASS__, 'load_extension_controller']);
        spl_autoload_register([__CLASS__, 'load_extension_view']);
	}
}