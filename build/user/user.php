<?php

class User {
    use trait_extension;

    public function __construct() {
        spl_autoload_register(['User', 'load_extension_class']);
        spl_autoload_register(['User', 'load_model']);
        spl_autoload_register(['User', 'load_controller']);
    }

    public function is_logined() {
        if(!empty($_COOKIE['user'])) {
            return true;
        } else {
            $session    = Application::get_class('Session');
            if($session->get_var('user')) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function get_id() {
        if($this->is_logined()) {
            $model  = $this->get_model('UserModel');
            $id = $model->get_id();
            return $id;
        } else {
            return 0;
        }
    }

    public function login($login, $password, $remember = false) {
        $model  = $this->get_model('UserModel');
        $result = $model->login($login, $password, $remember);
        return $result;
    }

    public function get_fields($uid = null) {
        $model  = $this->get_model('UserModel');
        return $model->get_fields($uid);
    }

    public function get_field($name, $uid = null) {
        return $this->get_fields($uid)[$name];
    }

    public function register($fields) {
        $model  = $this->get_model('UserModel');
        return $model->register($fields);
    }

    public function update_fields($fields, $uid = null) {
        $model  = $this->get_model('UserModel');
        $model->update_fields($fields, $uid);
    }

    public function get_users() {
        $model  = $this->get_model('UserModel');
        return $model->get_users();
    }

    public function log_out() {
        $model  = $this->get_model('UserModel');
        $model->log_out();
    }
}