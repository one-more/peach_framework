<?php

namespace Starter\view\AdminPanel;

class LeftMenuView extends \TemplateView {

    private $template_name = 'left_menu.tpl.html';

	public function __construct() {
		parent::__construct();

		$path = $this->template->get_path();
		$this->setTemplateDir($path.DS.'templates'.DS.'admin_panel'.DS.'left_menu');
	}

	public function render() {
		$this->assign($this->get_data());
		return $this->get_template($this->template_name);
	}

    private function get_data() {
        return [
            'url' => \Request::uri()
        ];
    }

	public function get_template_model() {
		$template_dir = $this->getTemplateDir(0);
		return new \TemplateViewModel([
			'name' => basename(str_replace('\\', '/', get_class($this))),
			'data' => array_merge([
				'lang_vars' => $this->get_lang_vars_array()
			], $this->get_data()),
			'html' => file_get_contents($template_dir.DS.$this->template_name)
		]);
	}
}