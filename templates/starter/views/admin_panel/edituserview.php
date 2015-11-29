<?php

namespace Starter\views\AdminPanel;

use common\classes\Application;
use common\views\TemplateView;

class EditUserView extends TemplateView {
    private $id;

	public function __construct($id) {
        $this->id = $id;
		parent::__construct();

		$path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'users');
	}

	public function render() {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();
        $identity = $mapper->find_by_id($this->id);
        $this->assign([
            'user' => $identity->to_array()
        ]);
		return $this->get_template('edit_user.tpl.html');
	}
}