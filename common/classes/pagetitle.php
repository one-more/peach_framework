<?php

namespace common\classes;

use common\routers\Router;

class PageTitle {

    /**
     * @var [] titles
     */
    private $titles = [];

    /**
     * @var string current
     */
    private $current = '';
    
    private $url;

    public function __construct($url = null) {
        $this->url = $url ?: Request::uri();
        $this->load_data();
    }

    private function load_data() {
        $file = ROOT_PATH.DS.'resource'.DS.CURRENT_LANG.'_titles.json';
        $area = strpos($this->url, 'admin_panel') === false ? 'site' : 'admin_panel';
        $this->titles = json_decode(file_get_contents($file), true)[$area];
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function set_current() {
        /**
         * @var $router Router
         */
        $router = Application::get_class(Router::class);
        $page_model = $router->get_page_model($this->url);

        if($page_model) {
            $title = !empty($this->titles[$page_model->name]) ? $this->titles[$page_model->name] : null;
            if(is_array($title)) {
                list($class, $method, $prefix) = array_pad($title, 3, '');
                try {
                    $reflection_method = new \ReflectionMethod($class, $method);
                    $params = array_merge([$prefix], $page_model->params);
                    if($reflection_method->isStatic()) {
                        $this->current = call_user_func_array([$class, $method], $params);
                    } else {
                        $this->current = call_user_func_array([
                            Application::get_class($class),
                            $method
                        ], $params);
                    }
                } catch(\Exception $e) {
                    Error::log($e->getMessage());
                }
            } else {
                $this->current = $title;
            }
        }
    }

    public function __toString() {
        try {
            $this->set_current();
            return (string)$this->current;
        } catch(\Exception $e) {
            return '';
        }
    }
}