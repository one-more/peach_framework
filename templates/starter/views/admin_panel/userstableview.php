<?php
namespace Starter\views\AdminPanel;

use common\classes\Application;
use common\classes\Error;
use common\views\TemplateView;

class UsersTableView extends TemplateView {
    private $page;

	public function __construct($page = 1) {
		parent::__construct();

        $path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'users');
		$this->add_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'common');
        $this->page = $page;
	}

	public function render() {
        /**
         * @var $ext \User
         */
        $ext = Application::get_class(\User::class);
        $user = $ext->get_identity();
        $mapper = $ext->get_mapper();
        $users = $mapper->get_page($this->page)->to_array();
        $is_super_admin = $user->is_super_admin();
        $this->assign([
            'users' => $users,
            'my_id' => $user->id,
            'is_super_admin' => $is_super_admin,
            'paging_model' => $mapper->get_paging($this->page)->to_array(),
            'base_url' => '/admin_panel/users'
        ]);
		return $this->get_template('users_table.tpl.html');
	}
}