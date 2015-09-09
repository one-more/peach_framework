<?php

/**
 * Class UserIdentity
 *
 * @property int id
 * @property string login
 * @property string password
 * @property string credentials
 * @property string remember_hash
 */
class UserIdentity extends RecordSet {

    public function __construct($fields) {
        $defaults = [
            'id' => 0,
            'login' => '',
            'password' => '',
            'credentials' => 'user',
            'remember_hash' => ''
        ];

        parent::__construct(array_merge($defaults, $fields));
    }

    public function is_guest() {
        return $this->id === 0;
    }

    public function is_admin() {
        return in_array($this->credentials, [
            User::credentials_admin,
            User::credentials_super_admin
        ], true);
    }

    public function is_super_admin() {
        return $this->credentials === User::credentials_super_admin;
    }
}