<?php
namespace AdminPanel;

class EditUserView extends \TemplateView {

	public function __construct($id) {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'edit_user');

        /**
         * @var $user \UserIdentity
         */
		$user = \Application::get_class('User')->get_current();
		$this->assign('user', $user);
	}

	public function render() {
		return $this->get_template('edit_user.tpl.html');
	}
}