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

    public static function load_template_class($name) {
        $name   = strtolower($name);
        $class  = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__).DS.'class'.DS.$class;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }

    public static function load_template_controller($name) {
        $name   = strtolower($name);
        $controller  = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__).DS.'controller'.DS.$controller;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }

    public function load_template_model($name) {
        $name   = strtolower($name);
        $model  = "{$name}.php";
        $file   = ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__).DS.'model'.DS.$model;
        if(file_exists($file)) {
            require_once $file;
        } else {
            return false;
        }
    }

    public function route() {
        $default    = [
            'controller'    => 'IndexController',
            'task'  => 'display',
            'params'    => []
        ];
        $params = array_merge($default, $_REQUEST);
        $controller = Application::get_class($params['controller']);
        $result = $controller->execute($default['task'], $params);
        if(!empty($_REQUEST['ajax']) && is_array($result)) {
            echo json_encode($result);
        }
        else {
            echo $result;
        }
    }
}