<?php
namespace AdminPanel;

class LoginFormView extends \TemplateView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel'.DS.'login');
	}

	public function render() {
		return $this->getTemplate('login.tpl.html');
	}
}