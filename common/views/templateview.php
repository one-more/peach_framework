<?php

namespace common\views;

use common\classes\Application;
use common\interfaces\Template;
use common\interfaces\View;
use common\traits\TraitView;

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
		$system = Application::get_class(\System::class);
		$template = $this->template = $system->template;
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