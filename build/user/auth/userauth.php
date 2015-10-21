<?php

namespace User\auth;
use common\classes\Application;
use common\classes\Error;
use common\classes\VarHandler;
use User\models\UserModel;

/**
 * Class UserAuth
 *
 * @decorate \common\decorators\AnnotationsDecorator
 */
class UserAuth {

    public function login($login, $password, $remember = false) {
        /**
         * @var $user \User
         */
        $user = Application::get_class(\User::class);
        $mapper = $user->get_mapper();

        $login = VarHandler::sanitize_var($login, 'string', '');
        $password = VarHandler::sanitize_var($password, 'string', '');
        $password = trim($password);

        $collection = $mapper->find_where([
            'login' => ['=', $login]
        ]);
        $result = false;
        $identity = null;
        if($collection->count()) {
            /**
             * @var $identity UserModel
             */
            $identity = $collection->one();
            if($password || trim($identity->password)) {
                if(password_verify($password, trim($identity->password))) {
                    $result = true;
                }
            } else {
                $result = true;
            }
        }
        if($result && $identity) {
            if(trim($identity->remember_hash) === '') {
                $identity->remember_hash = password_hash($identity->password.$identity->login, PASSWORD_DEFAULT);
                $mapper->save($identity);
            }
            if($remember) {
                setcookie('user', $identity->remember_hash, strtotime('+10 years'), '/');
                /*
                 * for tests
                 */
                $_COOKIE['user'] = $identity->remember_hash;
            } else {
                /**
                 * @var $session \Session
                 */
                $session = Application::get_class(\Session::class);
                $session->set_var('user', $identity->remember_hash);
            }
        }
        return $result;
    }

    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            unset($_COOKIE['user']);
            setcookie('user', null, -1, '/');
            return true;
        } else {
            /**
             * @var $session \Session
             */
            $session = Application::get_class(\Session::class);
            if($session->get_var('user')) {
                $session->unset_var('user');
                return true;
            }
            return false;
        }
    }
}