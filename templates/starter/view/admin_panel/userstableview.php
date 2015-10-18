<?php
namespace Starter\view\AdminPanel;

class UsersTableView extends \TemplateView {

    private $template_name = 'users_table.tpl.html';

	public function __construct() {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'users_table');
	}

	public function render() {
        $this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

	private function get_data() {
        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        $user = $ext->get_identity();
        $mapper = $ext->get_mapper();
        $users = $mapper->get_page();
        $is_super_admin = $user->is_super_admin();

        return [
            'users' => $users,
            'my_id' => $user->id,
            'is_super_admin' => $is_super_admin,
            'paging_model' => $mapper->getPaging()
        ];
    }

    public function get_template_model() {
        $template_dir = $this->getTemplateDir(0);
        $data = $this->get_data();
        $data['lang_vars'] = $this->get_lang_vars_array();
        $data['inclusions'] = [
            'pagination.tpl.html' => file_get_contents($template_dir.DS.'pagination.tpl.html')
        ];
        return new \TemplateViewModel([
            'name' => 'UsersTableView',
            'data' => $data,
            'html' => file_get_contents($template_dir.DS.$this->template_name)
        ]);
    }
}