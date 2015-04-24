<?php

class AdminPanelLeftMenu extends TemplateView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
	}

	public function render() {
		$this->assign('url', Request::uri());
		return $this->getTemplate('left_menu.tpl.html');
	}

	public function get_lang_file() {
		return $this->template->path.DS.'lang'.DS.CURRENT_LANG.DS.'admin_panel_left_menu.json';
	}
}