<?php
namespace Starter\view\AdminPanel;

use User\identity\UserIdentity;

class UsersTableView extends \TemplateView {
	public function __construct() {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'users_table');
	}

	public function render() {
        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        /**
         * @var $user UserIdentity
         */
		$user = $ext->get_current();
		$users = $ext->get_list();
		$this->assign('users', $users);
		$this->assign('my_id', $user->id);
		$is_super_admin = $user->is_super_admin();
		$this->assign('is_super_admin', $is_super_admin);
		return $this->get_template('users_table.tpl.html');
	}
}