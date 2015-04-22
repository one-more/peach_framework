<?php

abstract class SuperView extends Smarty {

	abstract function render();

	public function getTemplate($template = null, $cache_id = null, $compiled_id = null, $parent = null) {
		$this->load_lang_vars($this->get_lang_file());
		return parent::getTemplate($template, $cache_id, $compiled_id, $parent);
	}

	abstract function get_lang_file();

	protected function load_lang_vars($file) {
		if(file_exists($file)) {
			$lang_vars = json_decode(file_get_contents($file), true);
			$this->assign($lang_vars);
		}
	}
}