<?php

namespace common\routers;

use common\classes\Application;
use common\classes\Configuration;
use common\classes\Error;
use common\classes\Request;
use common\models\PageModel;

class Router {
	private $routes = [];
    private $area_router;

    public function route() {
        $page = $this->get_route();
        $area_router = Application::get_class($this->area_router);
        $this->navigate($area_router, $page, (array)$page->params);
    }

    /**
     * @param null $url
     * @return PageModel
     */
    public function get_page_model($url = null) {
        return $this->get_route($url);
    }

    private function init($request_uri) {
        /**
         * @var $configuration Configuration
         */
        $configuration = Application::get_class(Configuration::class);
        $pages = $configuration->pages;
        $area_routers = $configuration->routers;

        $uri_parts = array_values(array_filter(explode('/', $request_uri)));
        switch(reset($uri_parts)) {
            case 'admin_panel':
                $this->routes = $pages['admin_panel'];
                $this->area_router = $area_routers['admin_panel'];
                break;
            case 'rest':
                $this->routes = $pages['rest'];
                $this->area_router = $area_routers['rest'];
                break;
            case 'action':
                $this->routes = $pages['action'];
                $this->area_router = $area_routers['action'];
                break;
            default:
                $this->routes = $pages['site'];
                $this->area_router = $area_routers['site'];
        }
    }

    private function navigate(TemplateRouter $router, PageModel $page, array $params) {
        $router->navigate($page, $params);
    }

    /**
     * @param null|string $request_uri
     * @return PageModel
     */
	private function get_route($request_uri = null) {
        $request_uri = $request_uri ?: Request::uri();
        $this->init($request_uri);
		if(isset($this->routes[$request_uri])) {
			$fields = $this->routes[$request_uri];
            return new PageModel(array_merge($fields, [
                'params' => []
            ]));
		} else {
			$keys = array_keys($this->routes);
			foreach($keys as $key) {
				if(strpos($key, ':') !== false) {
					$parts = explode('/', $key);
					$parts_keys = array_keys($parts);
                    foreach($parts_keys as $part_key) {
                        $part = $parts[$part_key];
						$pos = strpos($part, ':');
						if($pos !== false) {
							$sub_str = substr($part, $pos+1);
							switch($sub_str) {
								case 'number':
                                    $parts[$part_key] = str_replace([$sub_str, ':'], ['(\d+)', ''], $part);
									break;
								case 'string':
								default:
                                    $parts[$part_key] = str_replace([$sub_str, ':'], ['(\w+)', ''], $part);
									break;
							}
						}
					}
					$parts = implode('\/', $parts);
					preg_match_all("/^$parts$/iU", $request_uri, $result, PREG_SET_ORDER);
					if(!empty($result[0]) && count($result)) {
						$route_params = array_slice($result[0], 1);
						$fields = $this->routes[$key];
                        return new PageModel(array_merge($fields, [
                            'params' => $route_params
                        ]));
					}
				}
			}
			foreach($keys as $key) {
				if(strpos($key, '*') !== false) {
					return new PageModel(array_merge($this->routes[$key], [
                        'params' => []
                    ]));
				}
			}
			return new PageModel();
		}
	}
} 