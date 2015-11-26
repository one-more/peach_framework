<?php

namespace common\classes;

use common\traits\TraitConfiguration;

class Configuration {
    use TraitConfiguration;

    /**
     * @var array
     */
    public $db_params;

    /**
     * @var string
     */
    public $language;

    /**
     * @var array
     */
    public $pages;

    /**
     * @var array
     */
    public $routers;

    public function __construct() {
        $this->load();
    }

    /**
     * @param string $value
     */
    public function set_language($value) {
        if(Request::get_var('language')) {
            setcookie('language', $value, strtotime('+10 years'), '/');
        } else {
            $this->set_params(['language'   => $value], 'configuration');
        }
    }

    private function load() {
        $configuration = $this->get_params('configuration');
        $this->load_db_params($configuration);
        $this->load_language($configuration);

        $this->pages = $configuration['pages'];
        $this->routers = $configuration['routers'];
    }

    private function load_db_params(array $configuration) {
        if($this->is_test_env()) {
            $this->db_params = $configuration['db_params']['tests'];
        } elseif(Application::is_dev()) {
            $this->db_params = $configuration['db_params']['development'];
        } else {
            $this->db_params = $configuration['db_params']['production'];
        }
    }

    private function load_language(array $configuration) {
        if(!empty($_COOKIE['language'])) {
            $this->language = $_COOKIE['language'];
        } else {
            $this->language = $configuration['language'];
        }
    }

    private function is_test_env() {
        return defined('TESTS_ENV') ? true : Application::is_dev() && Request::cookie('TEST_ENV');
    }
}