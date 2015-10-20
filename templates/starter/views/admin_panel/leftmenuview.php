<?php

namespace Starter\views\AdminPanel;

use classes\Request;
use views\TemplateView;

class LeftMenuView extends TemplateView {

    private $template_name = 'left_menu.tpl.html';

	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'left_menu');
	}

	public function render() {
		$this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

    public function get_data() {
        return [
            'url' => Request::uri()
        ];
    }

	public function get_template_name() {
        return $this->template_name;
    }
}