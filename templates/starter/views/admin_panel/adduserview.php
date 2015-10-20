<?php

namespace Starter\views\AdminPanel;

use common\views\TemplateView;

class AddUserView extends TemplateView {

    private $template_name = 'add_user.tpl.html';

	public function __construct() {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'add_user');
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