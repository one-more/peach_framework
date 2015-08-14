<?php

/**
 * Class UserController
 * @decorate AnnotationsDecorator
 */
class UserController {
	use trait_controller;

	public function is_token_valid() {
		$client_token = Request::get_var('token');
		unset($_GET['token']);
		$str_to_hash = Request::get_var('user', null, '')
			.Request::get_var('pfm_session_id', null, '')
			.json_encode(array_merge($_GET, $_POST), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
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
        /**
         * @var $user User
         */
		$user = Application::get_class('User');
		$response = new JsonResponse();
		$response->user = $user->login($login, $password, $remember);
        $response->status = !empty($response->user);
        return $response;
	}

	public function is_admin() {
		/**
         * @var $user User
         */
        $user = Application::get_class('User');
		return $user->get_field('credentials') == 'administrator' ||
			$user->get_field('credentials') == 'super_administrator';
	}

	public function is_super_admin() {
		/**
         * @var $user User
         */
        $user = Application::get_class('User');
		return $user->get_field('credentials') == 'super_administrator';
	}

    public function get_hash_password_rule($fields) {
        return function() use($fields) {
            return function($value, $undef, &$output_arr) use($fields) {
                if(trim($value)) {
                    $output_arr = $this->crypt_password($fields['login'], $value);
                    return;
                }
                return;
            };
        };
    }

	public function edit_user() {
        Application::init_validator();

        $user_data = [
            'login' => Request::get_var('login', 'string'),
            'password' => Request::get_var('password', 'string'),
            'credentials' => Request::get_var('credentials', 'string')
        ];

        $uid = Request::get_var('id', 'int');
        if(!$uid) {
            throw new InvalidArgumentException('empty id');
        }

        $validator = new Validator\LIVR([
            'login' => ['required', 'unique_login'],
            'password' => 'hash_password',
            'credentials' => 'required'
        ]);

        $validator->registerRules(['unique_login' => function() use($uid) {
            return function ($value) use($uid) {
                /**
                 * @var $user User
                 */
                $user = Application::get_class('User');
                $old_fields = $user->get_fields($uid);
                if($old_fields['login'] != $value) {
                    if($user->get_user_by_field('login', $value)) {
                        return 'LOGIN_EXISTS';
                    }
                }
            };
        }]);

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($user_data)]);

        $response = new JsonResponse();

		if(!$this->is_super_admin()) {
			throw new WrongRightsException('you must be super admin to edit users');
		}

		$lang_vars = $this->get_lang_vars();
        $fields = $validator->validate($user_data);
        if($fields) {
            /**
             * @var $user User
             */
            $user = Application::get_class('User');
            $user->update_fields($fields, $uid);
            $response->status = 'success';
            $response->message = $lang_vars['edit_user']['success'];
        } else {
            $errors = $validator->getErrors();
            Error::log(print_r($errors, 1));
            $response->status = 'error';
        }
        return $response;
	}

	public function add_user() {

        $response = new JsonResponse();

		if(!$this->is_super_admin()) {
			throw new Exception('you must be super admin to add users');
		}
		$lang_vars = $this->get_lang_vars();
		try {
			$fields = $this->get_sanitized_vars([
				[
					'name' => 'login',
					'required' => true,
					'error' => $lang_vars['add_user']['empty_login'],
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
					'error' => $lang_vars['add_user']['empty_credentials']
				]
			]);
		} catch(Exception $e) {
            $response->status = 'error';
            $response->message = $e->getMessage();
            return $response;
		}
        /**
         * @var $user User
         */
		$user = Application::get_class('User');
		if($user->get_user_by_field('login', $fields['login'])) {
            $response->status = 'error';
            $response->message = $lang_vars['add_user']['login_exists'];
            return $response;
		}
		if(trim($fields['password'])) {
			$fields['password'] =
				$this->crypt_password($fields['login'], $fields['password']);
		}
		$user->register($fields);

        $response->status = 'success';
        $response->message = $lang_vars['add_user']['success'];
        return $response;
	}
}