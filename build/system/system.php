<?php

use common\classes\Application;
use common\classes\PFMExtensionWrapper;

/**
 * Class System
 */
class System implements \common\interfaces\Extension {
    use \common\traits\TraitExtension;

    /**
     * @var $template \common\interfaces\Template
     */
    public $template;

    public function __construct() {
        $this->register_autoload();

        if(!in_array('pfmextension', stream_get_wrappers(), $strict = true)) {
            stream_wrapper_register('pfmextension', PFMExtensionWrapper::class);
        }

        $this->init_db();

        $this->set_template();
    }

    public function initialize() {
        mb_internal_encoding('UTF-8');
        mb_http_output('UTF-8');
        mb_http_input('UTF-8');

        \common\classes\Error::initialize();
        \System\handler\ExceptionHandler::initialize();

        /**
         * @var $session Session
         */
        $session = \common\classes\Application::get_class(Session::class);
        $session->start();
    }

    private function set_template() {
        /**
         * @var $tools Tools
         */
        $tools = Application::get_class(Tools::class);
        $mapper = $tools->get_templates_mapper();
        $template_model = $mapper->get_active();
        $this->template = Application::get_class($template_model->name);
    }

    private function init_db() {
        if(defined('TESTS_ENV') || Application::is_dev()) {
            $adapter = new \common\adapters\MysqlAdapter('');
            $sql = 'SHOW TABLES';
            $tables = ['users', 'session', 'templates'];
            if(count(array_intersect($adapter->execute($sql)->get_arrays(), $tables)) < count($tables)) {
                $sql = file_get_contents(ROOT_PATH.DS.'resource'.DS.'initialize.sql');
                $adapter->execute($sql);
            }
        }
    }
}