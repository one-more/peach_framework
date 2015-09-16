<?php

namespace User\auth;
use User\model\UserModel;

/**
 * Class UserAuth
 *
 * @decorate AnnotationsDecorator
 */
class UserAuth {

    /**
     * @var $model UserModel
     */
    private $model;

    public function __construct() {
        $this->model = \Application::get_class('User\model\UserModel');
    }

    public function login($login, $password, $remember = false) {
        $login = \VarHandler::sanitize_var($login, 'string', '');
        $password = \VarHandler::sanitize_var($password, 'string', '');
        $password = trim($password);
        if($password) {
            $password = $this->crypt_password($login, $password);
        }
        return $this->model->login($login, $password, $remember);
    }

    /**
     * @requestMethod Ajax
     */
    public function login_by_ajax() {
        $login = \Request::get_var('login', 'string');
        $password = \Request::get_var('password', 'string');
        $remember = \Request::get_var('remember', 'string', false);

        return $this->login($login, $password, (bool)$remember);
    }

    public function crypt_password($login, $password) {
        return crypt(trim($password), sha1($password).sha1($login).uniqid('password', true));
    }

    public function log_out() {
        return $this->model->log_out();
    }
}