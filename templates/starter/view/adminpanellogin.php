<?php

class AdminPanelLogin extends TemplateView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
	}

	public function render() {
		return $this->getTemplate('login.tpl.html');
	}

	public function get_lang_file() {
		return $this->template->path.DS.'lang'.DS.CURRENT_LANG.DS.'admin_panel_login.json';
	}
}