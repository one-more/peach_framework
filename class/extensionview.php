<?php

abstract class ExtensionView extends Smarty implements View {
    use TraitView;

    /**
     * @return Extension
     */
    public abstract function get_extension();

    /**
     * @return string
     */
    public abstract function render();

    public function __construct() {
        parent::__construct();

        /**
         * @var $extension Extension
         */
        $extension = $this->get_extension();
        $this->setCompileDir($extension->get_path().DS.'templates_c');
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