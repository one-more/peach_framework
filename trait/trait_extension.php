<?php

trait trait_extension {

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

    public static function load_extension_class($name) {
        $class_name = strtolower($name).'.php';
        $extension  = strtolower(__CLASS__).'.tar.gz';
        $file   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension.DS.'class'.DS.$class_name;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }

    public static function load_model($name) {
        $model  = strtolower($name).'.php';
        $extension  = strtolower(__CLASS__).'.tar.gz';
        $file   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension.DS.'model'.DS.$model;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }

    }

    public static function load_controller($name) {
        $controller = strtolower($name);
        $extension  = strtolower(__CLASS__).'.tar.gz';
        $file   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.$extension.DS.'class'.DS.$controller;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
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

    protected function array_to_json_string($array, $tabs = "\t") {
        $json_str   = "{\n";
        $json_chunks    = [];
        foreach($array as $k=>$el) {
            if(is_array($el)) {
                if(!$this->is_assoc_array($el)) {
                    $el = array_map(function($value){
                        return "\"{$value}\"";
                    }, $el);
                    $json_chunks[]  = "{$tabs}\"{$k}\"\t: [".implode(',', $el).']';
                } else {
                    $json_chunks[]   = "{$tabs}\"{$k}\"\t: ".$this->array_to_json_string($el, $tabs."\t");
                }
            } else {
                $json_chunks[]   = "{$tabs}\"{$k}\"\t: \"{$el}\"";
            }
        }
        $json_str   .= implode(",\n", $json_chunks);
        $json_str   .= "\n".preg_replace("/\t/",'',$tabs,1).'}';
        return $json_str;
    }

    protected function is_assoc_array($array) {
        foreach($array as $k=>$el) {
            if(!is_numeric($k)) {
                return true;
            }
        }
        return false;
    }
}