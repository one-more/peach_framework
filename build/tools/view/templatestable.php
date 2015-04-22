<?php

class TemplatesTable extends SuperView {

	public function __construct() {
		parent::__construct();
		$this->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'templates');
		$this->setCompileDir('pfmextension://tools'.DS.'templates_c');
	}

	public function render() {
		$controller = Application::get_class('TemplatesController');
		$templates_list = $controller->get_templates_list();
		$this->assign('templates_list', $templates_list);
		return $this->getTemplate('templates_table.tpl.html');
	}

	public function get_lang_file() {
		return 'pfmextension://tools'.DS.'lang'.DS.CURRENT_LANG.DS.'templates_table.json';
	}
}