<?php

namespace common\views;

use common\classes\Application;
use common\interfaces\Template;

abstract class TemplateView extends BaseView {

    /**
     * @var $template Template
     */
	protected $template;

	public function __construct() {
        /**
		 * @var $system \System
		 */
		$system = Application::get_class(\System::class);
		$template = $this->template = $system->template;
		$compile_dir = $template->get_path().DS.'templates_c';
		$this->set_compile_dir($compile_dir);
	}

    /**
     * @return null
     */
	public function get_lang_vars_base_dir() {
		return $this->template->get_lang_path();
	}
}