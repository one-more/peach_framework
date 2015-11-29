<?php

namespace Starter\views\AdminPanel;

use common\classes\Error;
use common\classes\Request;
use common\views\TemplateView;

class LeftMenuView extends TemplateView {

	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->set_template_dir($path.DS.'templates'.DS.'admin_panel'.DS.'left_menu');
	}

	public function render() {
		$this->assign([
			'url' => Request::uri()
		]);
		return $this->get_template('left_menu.tpl.html');
	}
}