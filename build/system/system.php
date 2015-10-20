<?php

/**
 * Class System
 */
class System implements \common\interfaces\Extension {
    use \common\traits\TraitExtension;

    public function initialize() {

        if(!in_array('pfmextension', stream_get_wrappers(), $strict = true)) {
            stream_wrapper_register('pfmextension', 'PFMExtensionWrapper');
        }

        $this->register_autoload();

        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

        \common\classes\Error::initialize();
        \System\handler\ExceptionHandler::initialize();

        $this->init_db();

        /**
         * @var $session Session
         */
        $session = \common\classes\Application::get_class(Session::class);
        $session->start();
    }

    public function get_configuration() {
        return $this->get_params('configuration');
    }

    public function get_template() {
        return $this->get_configuration()['template'];
    }

    private function init_db() {
        if(empty($this->get_params()['db_initialized'])) {
            $adapter = new \common\adapters\MysqlAdapter('');
            $sql = file_get_contents(ROOT_PATH.DS.'resource'.DS.'initialize.sql');
            $sql_chunks = explode("\n\n", $sql);
            foreach($sql_chunks as $el) {
                $adapter->execute($el);
            }
            $this->set_params(['db_initialized'=>true]);
        }
    }
}