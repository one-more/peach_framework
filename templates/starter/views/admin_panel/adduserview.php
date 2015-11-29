<?php

namespace Starter\views\AdminPanel;

use common\views\TemplateView;

class AddUserView extends TemplateView {

	public function __construct() {
		parent::__construct();

        $path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'users');
	}

	public function render() {
		return $this->get_template('add_user.tpl.html');
	}
}