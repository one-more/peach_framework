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

    public function __construct() {
        $this->load_data();
        $this->set_title();
    }

    private function load_data() {
        $file = ROOT_PATH.DS.'resource'.DS.CURRENT_LANG.'_titles.json';
        $area = strpos(Request::uri(), 'admin_panel') === false ? 'site' : 'admin_panel';
        $this->titles = json_decode(file_get_contents($file), true)[$area];
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function set_title() {
        /**
         * @var $router Router
         */
        $router = Application::get_class(Router::class);
        $pageModel = $router->current_page();
        if($pageModel) {
            $title = !empty($this->titles[$pageModel->name]) ? $this->titles[$pageModel->name] : null;
            if(is_array($title)) {
                list($class, $method, $prefix) = array_pad($title, 3, '');
                try {
                    $reflection_method = new \ReflectionMethod($class, $method);
                    $params = $router->get_route_params();
                    $params[] = $prefix;
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
        return (string)$this->current;
    }
}