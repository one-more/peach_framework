<?php

namespace common\views;

use common\classes\Error;
use common\classes\LanguageFile;
use common\helpers\StringHelper;
use common\interfaces\View;
use common\models\TemplateViewModel;

abstract class BaseView implements View {

    /**
     * @var \Smarty
     */
    protected $template_engine;

    protected $compile_dir;

    protected $template_dirs = [];

    /**
     * @param $path
     */
    public function set_compile_dir($path) {
        $this->compile_dir = $path;
    }

    public function set_template_dir($path) {
        $this->template_dirs[0] = $path;
    }

    public function add_template_dir($path) {
        $this->template_dirs[] = $path;
    }

    public function get_template_dir($index) {
        return $this->template_dirs[$index];
    }

    private function init_engine() {
        is_null($this->template_engine) && $this->template_engine = new \Smarty();
    }

    /**
     * @param null $template
     * @param null $cache_id
     * @param null $compiled_id
     * @param null $parent
     * @return string
     *
     * @throws \SmartyException
     */
    public function get_template($template = null, $cache_id = null, $compiled_id = null, $parent = null) {
        $this->init_engine();

        $this->template_engine->setCompileDir($this->compile_dir);
        foreach($this->template_dirs as $i=>$dir) {
            $i == 0 && $this->template_engine->setTemplateDir($dir);
            $i > 0 && $this->template_engine->addTemplateDir($dir);
        }

        $this->load_lang_vars($this->get_lang_file());
        return $this->template_engine->getTemplate($template, $cache_id, $compiled_id, $parent);
    }

    /**
     * @param $file
     */
    public function load_lang_vars($file) {
        $lang_file = new LanguageFile($file, $this->get_lang_vars_base_dir());
        $this->assign('lang_vars', $lang_file->get_data());
    }

    /**
     * @param $name
     * @param null $val
     * @param bool|false $no_cache
     */
    public function assign($name,  $val = null, $no_cache = false) {
        $this->init_engine();

        $this->template_engine->assign($name, $val, $no_cache);
    }

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
        $template_dir = $this->get_template_dir(0);
        return new TemplateViewModel([
            'name' => basename(str_replace('\\', '/', get_class($this))),
            'data' => array_merge([
                'lang_vars' => $this->get_lang_vars_array()
            ], $this->get_data()),
            'html' => file_get_contents($template_dir.DS.$this->get_template_name())
        ]);
    }

    /**
     * @return string
     */
    abstract public function render();
}