<?php

abstract class TemplateView extends SuperView {
	protected $template;

	public function __construct() {
		parent::__construct();
		/**
		 * @var $system System
		 */
		$system   = Application::get_class('System');
		$template = $this->template = Application::get_class($system->get_template());
		$compile_dir = $template->path.DS.'templates_c';
		$this->setCompileDir($compile_dir);
	}

	protected function get_lang_file() {
		$parts = explode('\\', get_class($this));
		$class = strtolower(array_pop($parts));
		$parts = array_map(['StringHelper', 'camelcase_to_dash'], $parts);
		$file = 'view'.DS.implode(DS, $parts).DS.$class.'.json';
		return $file;
	}
}