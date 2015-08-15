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

    /**
     * @param $login
     * @param $password
     * @param $remember
     * @return JsonResponse
     * @requestMethod Ajax
     */
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
        $response->status = !empty($response->user) ? 'success' : 'error';
        return $response;
	}

	public function is_admin() {
		/**
         * @var $user User
         */
        $user = Application::get_class('User');
        return in_array($user->get_field('credentials'), [
            'administrator',
            'super_administrator'
        ]);
	}

	public function is_super_admin() {
		/**
         * @var $user User
         */
        $user = Application::get_class('User');
		return $user->get_field('credentials') == 'super_administrator';
	}

    private function get_hash_password_rule($fields) {
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

    /**
     * @return JsonResponse
     * @throws InvalidArgumentException
     * @credentials super_admin
     * @credentialsError you must be super admin to edit users
     * @requestMethod Ajax
     */
	public function edit_user() {
        Application::init_validator();

        $user_data = [
            'login' => Request::get_var('login', 'string'),
            'password' => Request::get_var('password', 'string', ''),
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

		$lang_vars = $this->get_lang_vars();
        $fields = $validator->validate($user_data);
        if($fields) {
            /**
             * @var $user User
             */
            $user = Application::get_class('User');
            $user->update_fields($fields, $uid);
            $response->status = 'success';
            $response->message = $lang_vars['messages']['edited'];
        } else {
            $errors = $validator->getErrors($lang_vars['errors']);
            $response->errors = $errors;
            $response->status = 'error';
        }
        return $response;
	}

    /**
     * @return JsonResponse
     * @credentials super_admin
     * @credentialsError you must be super admin to add users
     * @requestMethod Ajax
     */
	public function add_user() {
        Application::init_validator();

        $user_data = [
            'login' => Request::get_var('login', 'string'),
            'password' => Request::get_var('password', 'string', ''),
            'credentials' => Request::get_var('credentials', 'string')
        ];

        $validator = new Validator\LIVR([
            'login' => ['required', 'unique_login'],
            'password' => 'hash_password',
            'credentials' => 'required'
        ]);

        $validator->registerRules(['unique_login' => function() {
            return function ($value) {
                /**
                 * @var $user User
                 */
                $user = Application::get_class('User');
                if(count($user->get_user_by_field('login', $value))) {
                    return 'LOGIN_EXISTS';
                }
            };
        }]);

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($user_data)]);

        $response = new JsonResponse();

		$lang_vars = $this->get_lang_vars();

        $fields = $validator->validate($user_data);
        if($fields) {
            /**
             * @var $user User
             */
            $user = Application::get_class('User');
            $user->register($fields);

            $response->status = 'success';
            $response->message = $lang_vars['messages']['added'];
        } else {
            $response->status = 'error';
            $response->errors = $validator->getErrors($lang_vars['errors']);
        }

        return $response;
	}
}