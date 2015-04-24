<?php

class UserController {
	use trait_controller;

	public function is_token_valid() {
		$client_token = Request::get_var('token');
		$str_to_hash = Request::get_var('user', null, '')
			.Request::get_var('pfm_session_id', null, '')
			.$_SERVER['HTTP_USER_AGENT'];
		return md5($str_to_hash) == $client_token;
	}

	private function crypt_password($login, $password) {
        return crypt(trim($password), md5($password).md5($login));
    }

	public function login($login, $password, $remember) {
		$password = trim($password);
		if($password) {
			$password = $this->crypt_password($login, $password);
		}
		$user = Application::get_class('User');
		return $user->login($login, $password, $remember);
	}

	public function is_admin() {
		$user = Application::get_class('User');
		return $user->get_field('credentials') == 'administrator' ||
			$user->get_field('credentials') == 'super_administrator';
	}
}