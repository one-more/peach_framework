<?php

abstract class SuperView extends Smarty {

	abstract function render();

	protected function get_language_vars($page) {
		$lang_obj = Application::get_class('Language');
		return $lang_obj->get_page($page);
	}
}