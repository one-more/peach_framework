<?php

namespace Starter\view\AdminPanel;

class NavbarView extends \TemplateView {
	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'navbar');
	}

	public function render() {
		return $this->get_template('navbar.tpl.html');
	}
}