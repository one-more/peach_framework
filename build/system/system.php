<?php

/**
 * Class System
 */
class System {
    use trait_extension;

    public function initialize() {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

		if(!in_array('pfmextension', stream_get_wrappers(), $strict = true)) {
			stream_wrapper_register('pfmextension', 'PFMExtensionWrapper');
		}

        $this->register_autoload();

        error::initialize();
        ExceptionHandler::initialize();

        $this->init_db();

        /**
         * @var $session Session
         */
        $session = Application::get_class('Session');
        $session->start();
    }

    public function get_configuration() {
        return $this->get_params('configuration');
    }

    public function get_template() {
        return $this->get_configuration()['template'];
    }

    public function get_use_db_param() {
        return (bool)$this->get_configuration()['use_db'];
    }

    public function set_use_db_param($param) {
        $this->set_params(['use_db' => (bool)$param], 'configuration');
    }

    private function init_db() {
        $params = $this->get_configuration();
        if($params['use_db'] && empty($this->get_params()['db_initialized'])) {
            $db_params = $params['db_params'];
            /**
             * @var $model SystemInitModel
             */
            $model = Application::get_class('SystemInitModel', $db_params);
            $model->initialize();
            $this->set_params(['db_initialized'=>true]);
        }
    }
}