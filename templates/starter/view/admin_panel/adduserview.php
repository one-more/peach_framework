<?php
namespace AdminPanel;

class AddUserView extends \TemplateView {

	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
	}

	public function render() {
		return $this->getTemplate('add_user.tpl.html');
	}
}