<?php

class System {
    use trait_extension;

    public function initialize() {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

        ini_set('display_errors', 'on');

        spl_autoload_register(['System', 'load_extension_class']);
        spl_autoload_register(['System', 'load_model']);
        spl_autoload_register(['System', 'load_controller']);

        error::initialize();
        ExceptionHandler::initialize();

        $this->init_db();

        $session    = Application::get_class('Session');
        $session->start();
    }

    public function get_configuration() {
        return $this->get_params('configuration');
    }

    protected function init_db() {
        $params = $this->get_configuration();
        if($params['use_db']) {
            if(empty($this->get_params('system')['db_initialized'])) {
                $db_params  = $params['db_params'];
                $model  = Application::get_class('SystemInitModel', $db_params);
                $model->initialize();
                $this->set_params('system', ['db_initialized'=>true]);
            }
        }
    }

    public function get_template() {
        return $this->get_configuration()['template'];
    }
}