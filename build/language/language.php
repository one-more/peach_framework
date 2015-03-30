<?php

class Language {
    use trait_extension;

    public function __construct() {
        spl_autoload_register(['Language', 'load_extension_class']);
        spl_autoload_register(['Language', 'load_model']);
        spl_autoload_register(['Language', 'load_controller']);

        if(!$this->initialized()) {
            $this->initialize();
            $system = Application::get_class('System');
            $default_language   = $system->get_configuration()['default_language'];
            $params = [
                'initialized'   => true,
                'language'  => $default_language
            ];
            $this->set_params('language', $params);
        }
        $this->import_variables();
    }

    public function get_language() {
        return $this->get_params()['language'];
    }

    public function set_language($lang) {
        $this->set_params('language', ['language'   => $lang]);
    }

    public function set_var($key, $value, $page = '') {
        $model  = $this->get_model('LanguageModel');
        $model->set_var($key, $value, $page);
    }

    public function get_var($name, $default = false) {
        $model  = $this->get_model('LanguageModel');
        return $model->get_var($name, $default);
    }

    public function unset_var($name) {
        $model  = $this->get_model('LanguageModel');
        $model->unset_var($name);
    }

    public function set_page($name, $variables) {
        $model  = $this->get_model('LanguageModel');
        $model->set_page($name, $variables);
    }

    public function get_page($page) {
        $model  = $this->get_model('LanguageModel');
        return $model->get_page($page);
    }

    public function unset_page($page) {
        $model  = $this->get_model('LanguageModel');
        return $model->unset_page($page);
    }

    protected function initialized() {
        $params = $this->get_params('language');
        return empty($params['initialized']) ? false : true;
    }

    protected function initialize() {
        $model  = $this->get_model('LanguageModel');
        $model->initialize();
    }

    protected function import_variables() {
        $params = $this->get_params();
        if($import_array    = empty($params['import']) ? false : $params['import']) {
            $model  = $this->get_model('LanguageModel');
            $model->import_variables($import_array);
            $this->unset_param('import');
        }
    }
}