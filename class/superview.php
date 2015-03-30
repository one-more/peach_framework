<?php

abstract class SuperView extends Smarty {
	protected $template;
	public function __construct() {
		parent::__construct();
		$system   = Application::get_class('System');
		$template = $this->template = Application::get_class($system->get_template());
		$compile_dir = $template->path.DS.'templates_c';
        $this->setCompileDir($compile_dir);
	}

	abstract function render();
}