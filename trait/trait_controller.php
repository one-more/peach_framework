<?php

trait trait_controller {

    protected function get_model($name) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class($name, $params);
        return $model;
    }

	protected function get_language_vars($page) {
		$lang_obj = Application::get_class('Language');
		return $lang_obj->get_page($page);
	}
}