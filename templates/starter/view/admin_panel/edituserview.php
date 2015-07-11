<?php
namespace AdminPanel;

class EditUserView extends \TemplateView {

	public function __construct($id) {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
		$user = \Application::get_class('User');
		$this->assign('user', $user->get_fields($id));
	}

	public function render() {
		return $this->getTemplate('edit_user.tpl.html');
	}
}