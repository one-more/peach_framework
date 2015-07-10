<?php

class Language {
	use trait_configuration;

	public function get_language() {
		return Request::get_var('language') ?
			Request::get_var('language') :
			$this->get_params('configuration')['language'];
	}

	public function set_language($lang) {
		if(Request::get_var('language')) {
			setcookie('language', $lang, strtotime('2037-12-31'), '/');
		} else {
			$this->set_params(['language'   => $lang], 'configuration');
		}
	}
}