<?php

class LeftMenu extends SuperView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir('pfmextension://tools'.DS.'templates'.DS.'menu');
		$this->setCompileDir('pfmextension://tools'.DS.'templates_c');
	}

	public function render() {
		$this->assign('uri', Request::uri());
		return $this->getTemplate('left_menu.tpl.html');
	}

	public function get_lang_file() {
		return 'pfmextension://tools'.DS.'lang'.DS.CURRENT_LANG.DS.'left_menu.json';
	}
}