<?php

namespace common\traits;
use common\classes\LanguageFile;
use common\helpers\StringHelper;
use common\models\TemplateViewModel;

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
        $parts = array_map([StringHelper::class, 'camelcase_to_dash'], $parts);
        return implode(DS, $parts).DS.$class.'.json';
    }

    public function get_template($template = null, $cache_id = null, $compiled_id = null, $parent = null) {
        $this->load_lang_vars($this->get_lang_file());
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

    /**
     * @return TemplateViewModel
     */
    public function get_template_model() {
        $template_dir = $this->getTemplateDir(0);
        return new TemplateViewModel([
            'name' => basename(str_replace('\\', '/', get_class($this))),
            'data' => array_merge([
                'lang_vars' => $this->get_lang_vars_array()
            ], $this->get_data()),
            'html' => file_get_contents($template_dir.DS.$this->get_template_name())
        ]);
    }
}