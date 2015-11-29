<?php

namespace User\models;
use common\classes\Application;
use common\models\BaseModel;

/**
 * Class UserModel
 * @package User\models
 *
 * @property int id
 * @property string login
 * @property string password
 * @property string credentials
 * @property string remember_hash
 */
class UserModel extends BaseModel {

    protected $fields = [
        'id' => null,
        'login' => null,
        'password' => '',
        'credentials' => \User::credentials_user,
        'remember_hash' => ''
    ];

    public function is_guest() {
        if(!empty($_COOKIE['user'])) {
            return $_COOKIE['user'] != $this->remember_hash;
        } else {
            /**
             * @var $session \Session
             */
            $session = Application::get_class(\Session::class);
            if($remember_hash = $session->get_var('user')) {
                return $remember_hash != $this->remember_hash;
            }
            return true;
        }
    }

    public function is_admin() {
        return in_array($this->credentials, [
            \User::credentials_admin,
            \User::credentials_super_admin
        ], true);
    }

    public function is_super_admin() {
        return $this->credentials === \User::credentials_super_admin;
    }

    public function to_array() {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'password' => $this->password,
            'credentials' => $this->credentials,
            'remember_hash' => $this->remember_hash,
            'is_admin' => $this->is_admin(),
            'is_super_admin' => $this->is_super_admin(),
            'is_guest' => $this->is_guest()
        ];
    }
}
