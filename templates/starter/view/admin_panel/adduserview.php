<?php
namespace AdminPanel;

class AddUserView extends \TemplateView {

	public function __construct() {
		parent::__construct();

        $path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'add_user');
	}

	public function render() {
		return $this->get_template('add_user.tpl.html');
	}
}