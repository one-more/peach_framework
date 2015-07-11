<?php

trait trait_controller {

	protected $language_vars;

    protected function get_model($name) {
		/**
		 * @var $system System
		 */
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class($name, $params);
        return $model;
    }

	protected function get_lang_vars() {
		if(empty($this->language_vars)) {
			$file = $this->get_lang_vars_file();
			if(file_exists($file)) {
				$this->language_vars = json_decode(file_get_contents($file), true);
			} else {
				$this->language_vars = [];
			}
		}
		return $this->language_vars;
	}

	protected function get_lang_vars_file() {
		/**
		 * @var $system System
		 */
		$system = Application::get_class('System');
		$template_name = $system->get_template();
		$template = Application::get_class($template_name);
		$path = $template->path.DS.'lang'.DS.CURRENT_LANG.DS;
		$path .= 'controller'.DS.strtolower(__CLASS__).'.json';
		return $path;
	}
}