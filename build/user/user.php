<?php

class User {
    use trait_extension;

    /**
     * @var $model UserModel
     */
	private $model;

    const credentials_user = 'user';

    const credentials_admin = 'administrator';

    const credentials_super_admin = 'super_administrator';

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
     * @return UserIdentity|null
     */
    public function get_identity_by_field($field, $value) {
        $fields = $this->model->get_user_by_field($field, $value);
        return !empty($fields) ? new UserIdentity($fields) : null;
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
                    /**
                     * @var $user_auth UserAuth
                     */
                    $user_auth = Application::get_class('User')->get_auth();
                    $output_arr = $user_auth->crypt_password($fields['login'], $value);
                    return;
                }
                return null;
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
            'credentials' => Request::get_var('credentials', 'string', 'user')
        ];

        $uid = Request::get_var('id', 'int');
        if(!$uid) {
            throw new InvalidArgumentException('empty id');
        }

        $response = new JsonResponse();

        $lang_vars = new LanguageFile('user.json', $this->get_path());
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

    /**
     * @param $fields
     * @param null $uid
     * @throws InvalidUserDataException
     */
    public function edit($fields, $uid = null) {
        Application::init_validator();

        $validator = new Validator\LIVR([
            'login' => ['required', 'unique_login'],
            'password' => 'hash_password',
            'credentials' => 'required'
        ]);

        $old_fields = $this->model->get_fields($uid);
        $fields = array_merge($old_fields, $fields);

        $validator->registerRules(['unique_login' => function() use($uid, $fields) {
            return function ($value) use($uid, $fields) {
                if($fields['login'] !== $value && $this->model->get_user_by_field('login', $value)) {
                        return 'LOGIN_EXISTS';
                }
                return null;
            };
        }]);

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($fields)]);

        if($validator->validate($fields)) {
            $this->model->update_fields($fields, $uid);
        } else {
            $lang_vars = new LanguageFile('user.json', $this->get_lang_path());
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
            'credentials' => Request::get_var('credentials', 'string', 'user')
        ];

        $response = new JsonResponse();

        $lang_vars = new LanguageFile('user.json', $this->get_lang_path());
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

    /**
     * @param $fields
     * @throws InvalidUserDataException
     */
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
                 * @var $user UserIdentity
                 */
                $user = Application::get_class('User')->get_identity_by_field('login', $value);
                if($user) {
                    return 'LOGIN_EXISTS';
                }
                return null;
            };
        }]);

        $validator->registerRules(['hash_password' => $this->get_hash_password_rule($fields)]);
        if($validator->validate($fields)) {
            $this->model->register($fields);
        } else {
            $lang_vars = new LanguageFile('user.json', $this->get_lang_path());
            throw new InvalidUserDataException((array)$validator->getErrors($lang_vars['errors']));
        }
    }

    public function delete($uid) {
        $this->model->delete_user($uid);
    }
}
