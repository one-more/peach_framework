<?php

namespace common\routers;

use common\classes\Application;
use common\classes\Configuration;
use common\classes\Error;
use common\classes\Request;
use common\models\PageModel;

class Router {
	private $routes = [];
	private $route_params = [];
    private $area_router;

    public function __construct() {
        /**
         * @var $configuration Configuration
         */
        $configuration = Application::get_class(Configuration::class);
        $pages = $configuration->pages;
        $area_routers = $configuration->routers;

        switch(array_replace_recursive([''], Request::uri_parts())[0]) {
            case 'admin_panel':
                $this->routes = $pages['admin_panel'];
                $this->area_router = Application::get_class($area_routers['admin_panel']);
                break;
            case 'rest':
                $routes = array_merge($pages['site'], $pages['admin_panel']);
                /**
                 * mix site and admin panel routes with other rest routes to not duplicate url's
                 */
                $routes = array_merge(array_combine(array_map(
                    function($el) {
                        return '/rest/templates'.$el;
                    },
                    array_keys($routes)
                ), $routes), $pages['rest']);
                $this->routes = $routes;
                $this->area_router = Application::get_class($area_routers['rest']);
                break;
            case 'action':
                $this->routes = $pages['action'];
                $this->area_router = Application::get_class($area_routers['action']);
                break;
            default:
                $this->routes = $pages['site'];
                $this->area_router = Application::get_class($area_routers['site']);
        }
    }

	public function route() {
		$page = $this->get_route();
        $this->navigate($this->area_router, new PageModel((array)$page), $this->route_params);
	}

    /**
     * @return PageModel|null
     */
    public function current_page() {
        $page = $this->get_route();
        return new PageModel((array)$page);
    }

    /**
     * @return array
     */
    public function get_route_params() {
        return $this->route_params;
    }

    private function navigate(TemplateRouter $router, PageModel $page, array $params) {
        $router->navigate($page, $params);
    }

	private function get_route() {
		$request_uri = Request::uri();
		if(isset($this->routes[$request_uri])) {
			return $this->routes[$request_uri];
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
						$this->route_params = array_slice($result[0], 1);
						return $this->routes[$key];
					}
				}
			}
			foreach($keys as $key) {
				if(strpos($key, '*') !== false) {
					return $this->routes[$key];
				}
			}
			return null;
		}
	}
} 