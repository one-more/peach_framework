<?php

class Language {
	use trait_configuration;

	public function get_language() {
		return Request::get_var('language') ?
			Request::get_var('language') :
			$this->get_params('configuration')['language'];
	}

	public function set_language($lang) {
		$this->set_params('configuration', ['language'   => $lang]);
	}
}