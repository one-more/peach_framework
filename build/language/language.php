<?php

class Language {
    use trait_extension;

    public function __construct() {
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
        return !empty($this->get_params('language')['initialized']);
    }

    protected function initialize() {
        $model  = $this->get_model('LanguageModel');
        $model->initialize();
    }
}