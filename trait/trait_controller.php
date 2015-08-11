<?php

trait trait_controller {

	protected $language_vars;

	protected function get_lang_vars() {
		return new LanguageFile($this->get_lang_vars_file());
	}

	protected function get_lang_vars_file() {
		return 'controller'.DS.strtolower(__CLASS__).'.json';
	}
}