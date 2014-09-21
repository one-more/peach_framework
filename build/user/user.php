<?php

class User {
    use trait_extension;

    public function __construct() {
        spl_autoload_register(['User', 'load_extension_class']);
        spl_autoload_register(['User', 'load_model']);
        spl_autoload_register(['User', 'load_controller']);
    }

    public function is_logined() {
        return !empty($_COOKIE['user_id']);
    }

    public function get_id() {
        if($this->is_logined()) {
            return intval($_COOKIE['user_id']);
        } else {
            return 0;
        }
    }

    public function login($login, $password) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class('UserModel', $params);
        return $model->login($login, $password);
    }

    public function get_fields($uid = null) {
        $system = Application::get_class('System');
        $params = $system->get_configuration()['db_params'];
        $model  = Application::get_class('UserModel', $params);
        return $model->get_fields($uid);
    }

    public function get_field($name, $uid = null) {
        return $this->get_fields($uid)[$name];
    }

    public function register($fields) {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        $model  = Application::get_class('UserModel', $params['db_params']);
        return $model->register($fields);
    }

    public function update_fields($fields, $uid = null) {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        $model  = Application::get_class('UserModel', $params['db_params']);
        $model->update_fields($fields, $uid);
    }

    public function get_users() {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        $model  = Application::get_class('UserModel', $params['db_params']);
        return $model->get_users();
    }
}