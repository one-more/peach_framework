<?php

trait trait_starter_router {

	public function route() {
		$callback = $this->get_callback();
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
			call_user_func_array($callback, $this->route_params);
		}
	}
}