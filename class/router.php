<?php

class Router {
	protected $routes = [];
	protected $route_params = [];

	public function __construct() {}

	public function set_routes($routes) {
		$this->routes = $routes;
	}

	public function route() {
		$callback = $this->get_callback();
		if($callback !== false) {
			call_user_func_array($callback, $this->route_params);
		}
	}

	protected function get_callback() {
		$request_uri = Request::uri();
		if(isset($this->routes[$request_uri])) {
			return $this->routes[$request_uri];
		} else {
			$keys = array_keys($this->routes);
			foreach($keys as $key) {
				if(strpos($key, ':') !== false) {
					$parts = explode('/', $key);
					foreach($parts as &$part) {
						$pos = strpos($part, ':');
						if($pos !== false) {
							$sub_str = substr($part, $pos+1);
							switch($sub_str) {
								case 'number':
									$part = str_replace([$sub_str, ':'], ['%d', ''], $part);
									break;
								case 'string':
								default:
									$part = str_replace([$sub_str, ':'], ['%s', ''], $part);
									break;
							}
						}
					}
					$parts = implode('/', $parts);
					$result = sscanf($request_uri, $parts);
					if(count($result) && !empty($result[0])) {
						$this->route_params = $result;
						return $this->routes[$key];
					}
				}
			}
			foreach($keys as $key) {
				if(strpos($key, '*') !== false) {
					return $this->routes[$key];
				}
			}
			return false;
		}
	}

	public function route_ajax() {
		$default    = [
			'controller'    => 'IndexController',
			'task'  => '_display',
			'params'    => []
		];
		$params = array_merge($default, $_REQUEST);
		$controller = Application::get_class($params['controller']);
		$task_params    = is_array($params['params']) ? $params['params'] : [$params['params']];
		$result = $controller->execute($params['task'], $task_params);
		return is_array($result) ? json_encode($result) : $result;
	}
} 