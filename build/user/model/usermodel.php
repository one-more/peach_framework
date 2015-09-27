<?php

namespace User\model;

/**
 * Class UserModel
 * @package User\model
 *
 * @property int id
 * @property string login
 * @property string password
 * @property string credentials
 * @property string remember_hash
 */
class UserModel extends \BaseModel {

    protected $fields = [
        'id' => null,
        'login' => null,
        'password' => null,
        'credentials' => null,
        'remember_hash' => null
    ];

    public function is_admin() {
        return in_array($this->credentials, [
            \User::credentials_admin,
            \User::credentials_super_admin
        ], true);
    }

    public function is_super_admin() {
        return $this->credentials === \User::credentials_super_admin;
    }
}