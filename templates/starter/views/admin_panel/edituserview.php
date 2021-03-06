<?php

namespace Starter\views\AdminPanel;

use common\classes\Application;
use common\views\TemplateView;

class EditUserView extends TemplateView {

    private $template_name = 'edit_user.tpl.html';
    private $id;

	public function __construct($id) {
        $this->id = $id;
		parent::__construct();

		$path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'users');
	}

	public function render() {
        $this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

    public function get_data() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $identity = $mapper->find_by_id($this->id);
        return [
            'user' => $identity->to_array()
        ];
    }

	public function get_template_name() {
        return $this->template_name;
    }
}