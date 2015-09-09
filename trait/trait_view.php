<?php

/**
 * Class trait_view
 *
 */
trait trait_view {

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
}