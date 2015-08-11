<?php

class LanguageFile implements ArrayAccess {
    private $data;
    private $base_path;

    public function __construct($file) {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        $template = Application::get_class($system->get_template());
        $this->base_path = $template->path.DS.'lang'.DS.CURRENT_LANG;
        $this->data = json_decode(file_get_contents($this->base_path.DS.$file), true);
    }

    public function get_data() {
        return $this->data;
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function &offsetGet($offset) {
        return $this->data;
    }

    public function __toString() {
        return json_encode($this->data);
    }
}