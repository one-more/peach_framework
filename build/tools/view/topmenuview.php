<?php

class TopMenuView extends ExtensionView {

	public function get_extension() {
        return Application::get_class('Tools');
    }

	public function __construct() {
		parent::__construct();

        $extension = $this->get_extension();
		$this->setTemplateDir($extension->get_path().DS.'templates'.DS.'menu');
	}

	public function render() {
		return $this->get_template('top_menu.tpl.html');
	}
}