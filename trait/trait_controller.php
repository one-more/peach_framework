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

    public function get_file($name) {
        if(strpos($name, 'js') !== false) {
            header('Content-type: application/javascript');
        } elseif(strpos($name, 'css') !== false) {
            header('Content-type: text/css');
        }
        if(in_array($name, array_keys($this->js_files))) {
            return file_get_contents($this->js_files[$name]);
        }
        if(in_array($name, array_keys($this->css_files))) {
            return file_get_contents($this->css_files[$name]);
        }
    }
}