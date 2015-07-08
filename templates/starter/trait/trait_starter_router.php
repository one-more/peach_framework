<?php

trait trait_starter_router {

	public function route() {
		$user_controller = Application::get_class('UserController');
		if(strtolower(__CLASS__) == 'adminpanelrouter' && !$user_controller->is_admin()) {
			$callback = [$this, 'index', 'no_check'];
		} else {
			$callback = $this->get_callback();
		}
		if($callback !== false) {
			$check = true;
			if(count($callback) == 3) {
				$check = (array_pop($callback) == 'check');
			}
			if($check) {
				$user_controller = Application::get_class('UserController');
				if(!$user_controller->is_token_valid()) {
					throw new Exception('invalid token');
				}
			}
			$class = $callback[0];
			if(is_string($class)) {
				$reflection = new ReflectionClass($class);
				$method = $reflection->getMethod($callback[1]);
				if(!$method->isStatic()) {
					$callback[0] = Application::get_class($class);
				}
			}
			call_user_func_array($callback, $this->route_params);
		}
	}
}