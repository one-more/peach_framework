<?php

class Session {
    use trait_extension;

    public function start() {
        spl_autoload_register(['Session', 'load_extension_class']);
        spl_autoload_register(['Session', 'load_model']);
        spl_autoload_register(['Session', 'load_controller']);

        $system = Application::get_class('System');
        $params = $system->get_configuration();
        if($params['use_db']) {
            if(empty($_COOKIE['pfm_session_id'])) {
                $model  = Application::get_class('SessionModel', $params['db_params']);
                $session_id = $model->start_session();
                setcookie('pfm_session_id', $session_id);
            }
        } else {
            session_start();
        }
    }

    public function get_id() {
        return $_COOKIE['pfm_session_id'];
    }

    public function get_var($name, $default = false) {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        if($params['use_db']) {
            $db_params  = $params['db_params'];
            $model  = Application::get_class('SessionModel', $db_params);
            return $model->get_var($name, $default);
        } else {
            return (empty($_SESSION[$name])) ? $default : $_SESSION[$name];
        }
    }

    public function set_var($name, $value) {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        if($params['use_db']) {
            $model  = Application::get_class('SessionModel', $params['db_params']);
            $model->set_var($name, $value);
        } else {
            $_SESSION[$name]    = $value;
        }
    }

    public function set_uid($uid) {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        if($params['use_db']) {
            $model  = Application::get_class('SessionModel', $params['db_params']);
            $model->set_uid($uid);
        }
    }
}