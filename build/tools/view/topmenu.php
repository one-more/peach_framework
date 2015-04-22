<?php

class TopMenu extends SuperView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'menu');
		$this->setCompileDir('pfmextension://tools'.DS.'templates_c');
	}

	public function render() {
		return $this->getTemplate('top_menu.tpl.html');
	}

	public function get_lang_file() {
		return 'pfmextension://tools'.DS.'lang'.DS.CURRENT_LANG.DS.'top_menu.json';
	}
}