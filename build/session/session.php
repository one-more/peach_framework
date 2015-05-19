<?php

class Session {
    use trait_extension;

	public function __construct() {
		$this->register_autoload();
	}

    public function start() {
        $system = Application::get_class('System');
        if($system->use_db()) {
            if(empty($_COOKIE['pfm_session_id'])) {
                $model  = $this->get_model('SessionModel');
                $session_id = $model->start_session();
                setcookie('pfm_session_id', $session_id, null, '/');
				return $session_id;
            } else {
				return $_COOKIE['pfm_session_id'];
			}
        } else {
            session_start();
			return session_id();
        }
    }

    public function get_id() {
        return empty($_COOKIE['pfm_session_id']) ? 0 : $_COOKIE['pfm_session_id'];
    }

    public function get_var($name, $default = false) {
        $system = Application::get_class('System');
        if($system->use_db()) {
            $model  = $this->get_model('SessionModel');
            return $model->get_var($name, $default);
        } else {
            return (empty($_SESSION[$name])) ? $default : $_SESSION[$name];
        }

    }

    public function set_var($name, $value) {
        $system = Application::get_class('System');
        if($system->use_db()) {
            $model  = $this->get_model('SessionModel');
            $model->set_var($name, $value);
        } else {
            $_SESSION[$name]    = $value;
        }
    }

    public function unset_var($name) {
        $system = Application::get_class('System');
        if($system->use_db()) {
            $model  = $this->get_model('SessionModel');
            $model->unset_var($name);
        } else {
            unset($_SESSION[$name]);
        }
    }

    public function set_uid($uid) {
        $system = Application::get_class('System');
        if($system->use_db()) {
            $model  = $this->get_model('SessionModel');
            $model->set_uid($uid);
        }
    }
}