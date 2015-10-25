<?php
namespace Starter\views\AdminPanel;

use common\classes\Application;
use common\models\TemplateViewModel;
use common\views\TemplateView;

class UsersTableView extends TemplateView {

    private $template_name = 'users_table.tpl.html';
    private $page;

	public function __construct($page = 1) {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'users');
		$this->addTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'common');
        $this->page = $page;
	}

	public function render() {
        $this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

	public function get_data() {
        /**
         * @var $ext \User
         */
        $ext = Application::get_class(\User::class);
        $user = $ext->get_identity();
        $mapper = $ext->get_mapper();
        $users = $mapper->get_page($this->page)->to_array();
        $is_super_admin = $user->is_super_admin();
        $template_dir = $this->getTemplateDir(1);

        return [
            'users' => $users,
            'my_id' => $user->id,
            'is_super_admin' => $is_super_admin,
            'paging_model' => $mapper->get_paging($this->page)->to_array(),
            'base_url' => '/admin_panel/users',
            'inclusions' => [
                'pagination.tpl.html' => file_get_contents($template_dir.DS.'pagination.tpl.html')
            ]
        ];
    }

    public function get_template_name() {
        return $this->template_name;
    }
}