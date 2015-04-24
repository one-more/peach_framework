<?php

class AdminPanelEditUser extends TemplateView {

	public function __construct($id) {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
		$user = Application::get_class('User');
		$this->assign('user', $user->get_fields($id));
	}

	public function render() {
		return $this->getTemplate('edit_user.tpl.html');
	}

	public function get_lang_file() {
		return $this->template->path.DS.'lang'.DS.CURRENT_LANG.DS.'admin_panel_edit_user.json';
	}
}