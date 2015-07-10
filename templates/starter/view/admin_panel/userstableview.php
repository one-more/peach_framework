<?php
namespace AdminPanel;

class UsersTable extends \TemplateView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
	}

	public function render() {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
		$users = $user->get_users();
		$this->assign('users', $users);
		$this->assign('my_id', $user->get_id());
		$is_super_admin = $user->get_field('credentials') == 'super_administrator';
		$this->assign('is_super_admin', $is_super_admin);
		return $this->getTemplate('users_table.tpl.html');
	}

	public function get_lang_file() {
		return $this->template->path.DS.'lang'.DS.CURRENT_LANG.DS.'admin_panel_users_table.json';
	}
}