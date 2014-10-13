<?php

trait trait_controller {
    public function execute($task, $params) {
        return call_user_func_array([$this, $task], $params);
    }

    public function __get($var) {
        switch($var) {
            case 'template':
                $system = Application::get_class('System');
                $this->template = $system->get_template();
                break;
            case 'js_files':
                $this->js_files    = [];
                break;
            case 'css_files':
                $this->css_files    = [];
                break;
        }
        return $this->$var;
    }

    public function get_js() {
        header('Content-type: application/javascript');
        $file   = '';
        foreach($this->js_files as $el) {
            $file   .= file_get_contents($el);
        }
        return $file;
    }

    public function get_css() {
        header('Content-type: text/css');
        $file   = '';
        foreach($this->css_files as $el) {
            $file   .= file_get_contents($el);
        }
        return $file;
    }

    public function get_file($path) {
        $system = Application::get_class('System');
        $template   = strtolower($system->get_template());
        if(strpos($path, 'js') !== false) {
            $file   = ROOT_PATH.DS.'templates'.DS.$template.DS.'js'.DS.$path;
            if(file_exists($file)) {
                header('Content-type: application/javascript');
                return file_get_contents($file);
            }
        } elseif(strpos($path, 'css') !== false) {
            $file   = ROOT_PATH.DS.'templates'.DS.$template.DS.'css'.DS.$path;
            header('Content-type: text/css');
            return file_get_contents($file);
        }
    }
}