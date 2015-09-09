<?php

class LanguageFile extends ArrayAccessObj {
    private $base_path;

    public function __construct($file, $base_dir = null) {
        if($base_dir) {
            $this->base_path = $base_dir;
        } else {
            /**
             * @var $system System
             */
            $system = Application::get_class('System');
            /**
             * @var $template Template
             */
            $template = Application::get_class($system->get_template());
            $this->base_path = $template->get_lang_path();
        }

        $file_path = $this->base_path.DS.$file;
        if(file_exists($file_path)) {
            parent::__construct(json_decode(file_get_contents($file_path), true));
        }
    }

    public function get_data() {
        return $this->values;
    }

    public function __toString() {
        return json_encode($this->values);
    }
}