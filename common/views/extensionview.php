<?php

namespace common\views;

use common\interfaces\Extension;

abstract class ExtensionView extends BaseView {

    /**
     * @return Extension
     */
    public abstract function get_extension();

    public function __construct() {
        /**
         * @var $extension Extension
         */
        $extension = $this->get_extension();
        $this->set_compile_dir($extension->get_path().DS.'templates_c');
    }

    /**
     * @return string
     */
    public function get_lang_vars_base_dir() {
		/**
         * @var $extension Extension
         */
        $extension = $this->get_extension();
        return $extension->get_lang_path();
	}
}