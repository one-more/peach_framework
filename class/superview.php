<?php

abstract class SuperView extends Smarty {

	abstract public function render();

	protected function get_lang_vars_base_dir() {
        return null;
    }

	public function getTemplate($template = null, $cache_id = null, $compiled_id = null, $parent = null) {
		$this->load_lang_vars($this->get_lang_file());
        $this->assign('App', new StaticClassDecorator('Application'));
		return parent::getTemplate($template, $cache_id, $compiled_id, $parent);
	}

	abstract protected function get_lang_file();

	protected function load_lang_vars($file) {
		$lang_file = new LanguageFile($file, $this->get_lang_vars_base_dir());
        $this->assign('lang_vars', $lang_file->get_data());
	}
}