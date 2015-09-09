<?php
namespace AdminPanel;

class LoginFormView extends \TemplateView {
	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'login');
	}

	public function render() {
		return $this->get_template('login.tpl.html');
	}
}