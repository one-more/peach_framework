<?php

class TemplatesTableView extends SuperView {

	public function __construct() {
		parent::__construct();
		$tools = Application::get_class('Tools');
		$this->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'templates');
	}

	public function render() {
		$controller = Application::get_class('TemplatesController');
		$templates_list = $controller->get_templates_list();
		$this->assign('templates_list', $templates_list);
		return $this->getTemplate('templates_table.tpl.html');
	}
}