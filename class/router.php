<?php

class Router {
	protected $routes = [];
	protected $route_params = [];

	public function set_routes($routes) {
		$this->routes = $routes;
	}

	public function route() {
		$callback = $this->get_callback();
		if($callback !== false) {
			if(is_array($callback)) {
				$class = $callback[0];
				if(is_string($class)) {
					$reflection = new ReflectionClass($class);
					$method = $reflection->getMethod($callback[1]);
					if(!$method->isStatic()) {
						$callback[0] = Application::get_class($class);
					}
				}
                if(count($callback) > 2) {
                    $callback = array_slice($callback, 0, 2);
                }
			}
			return call_user_func_array($callback, $this->route_params);
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
									$part = str_replace([$sub_str, ':'], ['(\d+)', ''], $part);
									break;
								case 'string':
								default:
									$part = str_replace([$sub_str, ':'], ['(\w+)', ''], $part);
									break;
							}
						}
					}
					$parts = implode('\/', $parts);
					preg_match_all("/^$parts$/iUs", $request_uri, $result, PREG_SET_ORDER);
					if(count($result) && !empty($result[0])) {
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
			return false;
		}
	}
} 