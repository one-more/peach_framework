<?php

trait trait_controller {

    protected function get_model($name) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class($name, $params);
        return $model;
    }

	protected function get_lang_vars() {
		$system = Application::get_class('System');
		$template_name = $system->get_template();
		$template = Application::get_class($template_name);
		$file = $template->path.DS.'lang'.DS.CURRENT_LANG.DS.strtolower(__CLASS__).'.json';
		if(file_exists($file)) {
			return json_decode(file_get_contents($file), true);
		} else {
			return [];
		}
	}
}