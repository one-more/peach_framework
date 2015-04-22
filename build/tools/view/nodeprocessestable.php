<?php

class NodeProcessesTable extends SuperView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'node_processes');
		$this->setCompileDir('pfmextension://tools'.DS.'templates_c');
	}

	public function render() {
		$controller = Application::get_class('NodeProcessesController');
		$processes = $controller->get_processes_list();
		$this->assign('processes', $processes);
		return $this->getTemplate('node_processes_table.tpl.html');
	}

	public function get_lang_file() {
		return 'pfmextension://tools'.DS.'lang'.DS.CURRENT_LANG.DS.'node_processes_table.json';
	}
}