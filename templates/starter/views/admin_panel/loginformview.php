<?php

namespace Starter\views\AdminPanel;

use common\views\TemplateView;

class LoginFormView extends TemplateView {

    private $template_name = 'login.tpl.html';

	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'login');
	}

	public function render() {
		return $this->get_template($this->template_name);
	}

	public function get_data() {
        return [];
    }

    public function get_template_name() {
        return $this->template_name;
    }
}