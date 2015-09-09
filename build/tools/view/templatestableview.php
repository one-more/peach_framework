<?php

class TemplatesTableView extends ExtensionView {

	public function get_extension() {
        return Application::get_class('Tools');
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'templates');
	}

	public function render() {
        /**
         * @var $controller TemplatesController
         */
		$controller = Application::get_class('TemplatesController');
		$templates_list = $controller->get_templates_list();
		$this->assign('templates_list', $templates_list);
		return $this->get_template('templates_table.tpl.html');
	}
}