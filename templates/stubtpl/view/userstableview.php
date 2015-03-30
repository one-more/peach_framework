<?php
class UsersTableView extends SuperView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'users');
	}

	public function render() {
		$controller = Application::get_class('IndexController');
		$users = $controller->get_users();
		$lang   = Application::get_class('Language');
		$lang_vars  = $lang->get_page('StubTpl::index');
		$system = Application::get_class('System');
        $this->assign('use_db_param', $system->use_db());
		$this->assign($lang_vars);
		$this->assign('users_array', $users);
		return $this->getTemplate('users_table.tpl.html');
	}
}