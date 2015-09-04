<?php

class User {
    use trait_extension;

    /**
     * @var $model UserModel
     */
	private $model;

    public function __construct() {
        $this->register_autoload();
		$this->model = Application::get_class('UserModel');
    }

    /**
     * @return UserAuth
     */
    public function get_auth() {
        return Application::get_class('UserAuth');
    }

    /**
     * @param null $id
     * @return UserIdentity
     */
    public function get_identity($id = null) {
        return new UserIdentity($this->model->get_fields($id));
    }

    /**
     * @param $field
     * @param $value
     * @return UserIdentity
     */
    public function get_identity_by_field($field, $value) {
        return new UserIdentity($this->model->get_user_by_field($field, $value));
    }

    /**
     * @param null $ids
     * @return UserIdentity[]
     */
    public function get_list($ids = null) {
        return array_map(function($fields) {
            return new UserIdentity($fields);
        }, $this->model->get_users($ids));
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
	public function edit_by_ajax() {
        $user_data = [
            'login' => Request::get_var('login', 'string'),
            'password' => Request::get_var('password', 'string', ''),
            'credentials' => Request::get_var('credentials', 'string')
        ];

        $uid = Request::get_var('id', 'int');
        if(!$uid) {
            throw new InvalidArgumentException('empty id');
        }

        $response = new JsonResponse();

        $lang_vars = new LanguageFile('user.json', $this->lang_path);
        try {
            $this->edit($user_data, $uid);
            $response->status = 'success';
            $response->message = $lang_vars['messages']['edited'];
        } catch(InvalidUserDataException $e) {
            $response->status = 'error';
            $response->message = $e->errors;
        }
        return $response;
	}

    public function edit($fields, $uid = null) {
        Application::init_validator();

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

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($fields)]);

        if($validator->validate($fields)) {
            $this->model->update_fields($fields, $uid);
        } else {
            $lang_vars = new LanguageFile('user.json', $this->lang_path);
            throw new InvalidUserDataException((array)$validator->getErrors($lang_vars['errors']));
        }
    }

    /**
     * @return JsonResponse
     * @credentials super_admin
     * @credentialsError you must be super admin to add users
     * @requestMethod Ajax
     */
	public function add_by_ajax() {

        $user_data = [
            'login' => Request::get_var('login', 'string'),
            'password' => Request::get_var('password', 'string', ''),
            'credentials' => Request::get_var('credentials', 'string')
        ];

        $response = new JsonResponse();

        $lang_vars = new LanguageFile('user.json', $this->lang_path);
        try {
            $this->add($user_data);
            $response->status = 'success';
            $response->message = $lang_vars['messages']['added'];
        } catch(InvalidUserDataException $e) {
            $response->status = 'error';
            $response->errors = $e->errors;
        }

        return $response;
	}

    public function add($fields) {
        Application::init_validator();

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

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($fields)]);
        if($validator->validate($fields)) {
            $this->model->register($fields);
        } else {
            $lang_vars = new LanguageFile('user.json', $this->lang_path);
            throw new InvalidUserDataException((array)$validator->getErrors($lang_vars['errors']));
        }
    }

    public function delete($uid) {
        $this->model->delete_user($uid);
    }
}
