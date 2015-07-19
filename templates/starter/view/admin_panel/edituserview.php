<?php
namespace AdminPanel;

class EditUserView extends \TemplateView {

	public function __construct($id) {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
        /**
         * @var $user \User
         */
		$user = \Application::get_class('User');
        $fields = $user->get_fields($id);
		$this->assign('user', $fields);
	}

	public function render() {
		return $this->getTemplate('edit_user.tpl.html');
	}
}