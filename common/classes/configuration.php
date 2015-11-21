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
        $this->load_pages($configuration);
        $this->load_routers($configuration);
    }

    private function load_db_params(array $configuration) {
        if(defined('TESTS_ENV')) {
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

    private function load_pages(array $configuration) {
        $this->pages = $configuration['pages'];
    }

    private function load_routers(array $configuration) {
        $this->routers = $configuration['routers'];
    }
}