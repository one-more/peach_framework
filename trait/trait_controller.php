<?php

trait trait_controller {

    protected function get_model($name) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class($name, $params);
        return $model;
    }

	protected function get_lang_vars() {
		$file = $this->get_lang_vars_file();
		if(file_exists($file)) {
			return json_decode(file_get_contents($file), true);
		} else {
			return [];
		}
	}

	protected function get_lang_vars_file() {
		$system = Application::get_class('System');
		$template_name = $system->get_template();
		$template = Application::get_class($template_name);
		return $template->path.DS.'lang'.DS.CURRENT_LANG.DS.strtolower(__CLASS__).'.json';
	}
}