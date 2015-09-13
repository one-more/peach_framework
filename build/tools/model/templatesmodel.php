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
        return $ext->get_path().DS.'resource'.DS.'templates_list.json';
    }

    /**
     * @return array
     */
    public function get_list() {
        return (array)$this->data;
    }

    /**
     * @param $id
     * @return array
     */
    public function get_one($id) {
        return empty($this->data[$id]) ? [] : (array)$this->data[$id];
    }
}