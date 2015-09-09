<?php

class NodeProcessesTableView extends ExtensionView {

	public function get_extension() {
        return Application::get_class('Tools');
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'node_processes');
	}

	public function render() {
        /**
         * @var $controller NodeProcessesController
         */
		$controller = Application::get_class('NodeProcessesController');
		$processes = $controller->get_processes_list();
		$this->assign('processes', $processes);
		return $this->get_template('node_processes_table.tpl.html');
	}
}