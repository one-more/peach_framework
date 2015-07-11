<?php
namespace AdminPanel;

class LeftMenuView extends \TemplateView {
	public function __construct() {
		parent::__construct();
		$this->setTemplateDir($this->template->path.DS.'templates'.DS.'admin_panel');
	}

	public function render() {
		$this->assign('url', \Request::uri());
		return $this->getTemplate('left_menu.tpl.html');
	}
}