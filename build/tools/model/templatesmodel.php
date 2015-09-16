<?php

namespace Tools\model;

class TemplatesModel extends \FileModel {

    /**
     * @return string
     * @throws \InvalidArgumentException
     */
    public function get_file() {
        /**
         * @var $ext \Tools
         */
        $ext = \Application::get_class('Tools');
        return $ext->get_path().DS.'resource'.DS.'templates_model.json';
    }
}