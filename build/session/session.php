<?php

class Session {
    use trait_extension;

	public function __construct() {
		$this->register_autoload();
	}

    public function start() {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        if($system->get_use_db_param()) {
            if(empty($_COOKIE['pfm_session_id'])) {
                /**
                 * @var $model SessionModel
                 */
                $model  = Application::get_class('SessionModel');
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

    /**
     * @param $name
     * @param bool|false $default
     * @return bool
     */
    public function get_var($name, $default = false) {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        if($system->get_use_db_param()) {
            /**
             * @var $model SessionModel
             */
            $model  = Application::get_class('SessionModel');
            return $model->get_var($name, $default);
        } else {
            return (empty($_SESSION[$name])) ? $default : $_SESSION[$name];
        }

    }

    /**
     * @param $name
     * @param $value
     */
    public function set_var($name, $value) {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        if($system->get_use_db_param()) {
            /**
             * @var $model SessionModel
             */
            $model  = Application::get_class('SessionModel');
            $model->set_var($name, $value);
        } else {
            $_SESSION[$name]    = $value;
        }
    }

    public function unset_var($name) {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        if($system->get_use_db_param()) {
            /**
             * @var $model SessionModel
             */
            $model  = Application::get_class('SessionModel');
            $model->unset_var($name);
        } else {
            unset($_SESSION[$name]);
        }
    }

    public function set_uid($uid) {
        /**
         * @var $system System
         */
        $system = Application::get_class('System');
        if($system->get_use_db_param()) {
            /**
             * @var $model SessionModel
             */
            $model  = Application::get_class('SessionModel');
            $model->set_uid($uid);
        }
    }
}