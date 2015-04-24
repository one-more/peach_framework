<?php

class UserController {
	use trait_controller, trait_validator;

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

	public function edit_user() {
		$lang_vars = $this->get_lang_vars();
		try {
			$fields = $this->get_sanitized_vars([
				[
					'name' => 'login',
					'required' => true,
					'error' => $lang_vars['edit_user']['empty_login'],
					'type' => 'string'
				],
				[
					'name' => 'password',
					'type' => 'string'
				],
				[
					'name' => 'credentials',
					'required' => true,
					'type' => 'string',
					'error' => $lang_vars['edit_user']['empty_credentials']
				]
			]);
		} catch(Exception $e) {
			$error = [
				'status' => 'error',
				'message' => $e->getMessage()
			];
			echo json_encode($error);
		}
		$uid = Request::get_var('id', 'int');
		if(!$uid) {
			throw new Exception('empty id');
		}
		$user = Application::get_class('User');
		$old_fields = $user->get_fields($uid);
		if($old_fields['login'] != $fields['login']) {
			if($user->get_user_by_field('login', $fields['login'])) {
				echo json_encode([
					'status' => 'error',
					'message' => $lang_vars['edit_user']['login_exists']
				]);
			}
		}
		if(trim($fields['password'])) {
			$fields['password'] =
				$this->crypt_password($fields['login'], $fields['password']);
		}
		$user->update_fields($fields, $uid);
		echo json_encode([
			'status' => 'success',
			'message' => $lang_vars['edit_user']['success']
		]);
	}
}