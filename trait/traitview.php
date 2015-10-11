<?php

/**
 * Class TraitView
 *
 */
trait TraitView {

    /**
     * @return string
     */
    public function get_lang_file() {
        $parts = explode('\\', get_class($this));
        /*
         * remove extension|templates name
         */
        $parts = array_slice($parts, 1);
        $class = strtolower(array_pop($parts));
        $parts = array_map(['StringHelper', 'camelcase_to_dash'], $parts);
        return implode(DS, $parts).DS.$class.'.json';
    }

    public function get_template($template = null, $cache_id = null, $compiled_id = null, $parent = null) {
        $this->load_lang_vars($this->get_lang_file());
        $this->assign('HTML', new StaticClassDecorator('HTMLHelper'));
        $this->assign('BEM', new StaticClassDecorator('BEMHelper'));
        return parent::getTemplate($template, $cache_id, $compiled_id, $parent);
    }

    public function load_lang_vars($file) {
        $lang_file = new LanguageFile($file, $this->get_lang_vars_base_dir());
        $this->assign('lang_vars', $lang_file->get_data());
    }

    /**
     * @return array
     */
    public function get_lang_vars_array() {
        return (new LanguageFile($this->get_lang_file(), $this->get_lang_vars_base_dir()))->get_data();
    }
}