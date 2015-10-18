<?php
namespace Starter\view\AdminPanel;

class UsersTableView extends \TemplateView {

    private $template_name = 'users_table.tpl.html';
    private $page;

	public function __construct($page = 1) {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'users_table');
        $this->page = $page;
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
        $users = $mapper->get_page($this->page)->to_array();
        $is_super_admin = $user->is_super_admin();

        return [
            'users' => $users,
            'my_id' => $user->id,
            'is_super_admin' => $is_super_admin,
            'paging_model' => $mapper->getPaging($this->page)->to_array()
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
            'name' => basename(str_replace('\\', '/', get_class($this))),
            'data' => $data,
            'html' => file_get_contents($template_dir.DS.$this->template_name)
        ]);
    }
}