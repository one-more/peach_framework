<?php

namespace Starter\view\AdminPanel;

class NavbarView extends \TemplateView {

    private $template_name = 'navbar.tpl.html';

	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'navbar');
	}

	public function render() {
		return $this->get_template($this->template_name);
	}

	public function get_template_model() {
		$template_dir = $this->getTemplateDir(0);
		return new \TemplateViewModel([
			'name' => basename(str_replace('\\', '/', get_class($this))),
			'data' => [
				'lang_vars' => $this->get_lang_vars_array()
			],
			'html' => file_get_contents($template_dir.DS.$this->template_name)
		]);
	}
}