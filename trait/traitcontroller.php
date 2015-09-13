<?php

trait TraitController {

	protected $language_vars;

	protected function get_lang_vars() {
		$lang_file = new LanguageFile($this->get_lang_vars_file());
		return $lang_file->get_data();
	}

	protected function get_lang_vars_file() {
		return 'controller'.DS.strtolower(__CLASS__).'.json';
	}
}