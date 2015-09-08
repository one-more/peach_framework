<?php

class UserAuth {

    /**
     * @var $model UserModel
     */
    private $model;

    public function __construct() {
        $this->model = Application::get_class('UserModel');
    }

    public function login($login, $password, $remember = false) {
        $password = trim($password);
        if($password) {
            $password = $this->crypt_password($login, $password);
        }
        return $this->model->login($login, $password, $remember);
    }

    private function crypt_password($login, $password) {
        return crypt(trim($password), md5($password).md5($login));
    }

    public function log_out() {
        return $this->model->log_out();
    }
}