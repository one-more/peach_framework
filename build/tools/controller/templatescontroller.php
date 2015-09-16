<?php

namespace Tools\controller;

use Tools\model\TemplatesModel;
use Tools\record\TemplateRecord;

class TemplatesController {
	use \TraitController;

    /**
     * @var $model TemplatesModel
     */
	private $model;

    public function __construct() {
        $this->model = \Application::get_class('Tools\model\TemplatesModel');
    }

    /**
     * @return array
     */
	public function get_templates_list() {
		return array_map(function($fields) {
            return new TemplateRecord($fields);
        }, $this->model->select()->toArray());
	}

    /**
     * @param $id
     * @return null|TemplateRecord
     */
    public function get_template($id) {
        $fields = $this->model->select()->where(function($record) use($id) {
            return $record['id'] == $id;
        })->firstOrDefault(null);
        return count($fields) ? new TemplateRecord($fields) : null;
    }
}