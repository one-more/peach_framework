<?php

class IndexController {
    use trait_controller;

	public function get_users() {
		$user   = Application::get_class('User');
		return $user->get_users();
	}
}