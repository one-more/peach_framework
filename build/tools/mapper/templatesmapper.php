<?php

namespace Tools\mapper;

class TemplatesMapper extends \BaseMapper {

    public function get_adapter() {
        /**
         * @var $ext \Tools
         */
        $ext = \Application::get_class('Tools');
        return new \FileAdapter($ext->get_path().DS.'resource'.DS.'templates.json');
    }

    public function get_page($number = 1, $per_page = 30) {
        return $this->adapter->select();
    }
}