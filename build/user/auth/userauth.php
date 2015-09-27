<?php

namespace User\auth;
use User\model\UserModel;

/**
 * Class UserAuth
 *
 * @decorate AnnotationsDecorator
 */
class UserAuth {

    public function login($login, $password, $remember = false) {
        /**
         * @var $user \User
         */
        $user = \Application::get_class('User');
        $mapper = $user->get_mapper();

        $login = \VarHandler::sanitize_var($login, 'string', '');
        $password = \VarHandler::sanitize_var($password, 'string', '');
        $password = trim($password);
        if($password) {
            $password = $mapper->crypt_password($login, $password);
        }

        $collection = $mapper->find_where([
            'login' => ['=', $login],
            'and' => [
                'password' => ['=', $password]
            ]
        ]);
        if($collection->count()) {
            /**
             * @var $identity UserModel
             */
            $identity = $collection->one();
            if($remember) {
                setcookie('user', $identity->remember_hash, strtotime('+10 years'), '/');
                $_COOKIE['user'] = $identity->remember_hash;
            } else {
                /**
                 * @var $session \Session
                 */
                $session = \Application::get_class('Session');
                $session->set_var('user', $identity->remember_hash);
            }
            return true;
        } else {
            return false;
        }
    }

    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            return true;
        } else {
            /**
             * @var $session \Session
             */
            $session = \Application::get_class('Session');
            if($session->get_var('user')) {
                $session->unset_var('user');
                return true;
            }
            return false;
        }
    }
}