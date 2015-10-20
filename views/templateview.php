<?php

namespace views;

use classes\Application;
use interfaces\Template;
use interfaces\View;
use traits\TraitView;

abstract class TemplateView extends \Smarty implements View {
    use TraitView;

    /**
     * @var $template Template
     */
	protected $template;

	public function __construct() {
		parent::__construct();

        /**
		 * @var $system \System
		 */
		$system = Application::get_class('\System');
		$template = $this->template = Application::get_class($system->get_template());
		$compile_dir = $template->get_path().DS.'templates_c';
		$this->setCompileDir($compile_dir);
	}

    /**
     * @return string
     */
	abstract public function render();

    /**
     * @return null
     */
	public function get_lang_vars_base_dir() {
		return $this->template->get_lang_path();
	}
}