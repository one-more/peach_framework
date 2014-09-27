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
    }

    protected function initialized() {
        $params = $this->get_params('language');
        return empty($params['initialized']) ? false : true;
    }

    protected function initialize() {
        $model  = $this->get_model('LanguageModel');
        $model->initialize();
    }
}