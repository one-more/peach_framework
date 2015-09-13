<?php

class Session {
    use TraitExtension;

    /**
     * @var $model \Session\model\SessionModel
     */
    private $model;

	public function __construct() {
        $this->model = \Application::get_class('\Session\model\SessionModel');
		$this->register_autoload();
	}

    /**
     * @return int
     * @throws InvalidArgumentException
     */
    public function start() {
        /**
         * @var $system System
         */
        $system = \Application::get_class('System');
        if($system->get_use_db_param()) {
            if(empty($_COOKIE['pfm_session_id'])) {
                $session_id = $this->model->start_session();
                setcookie('pfm_session_id', $session_id, null, '/');
				return (int)$session_id;
            } else {
				return (int)$_COOKIE['pfm_session_id'];
			}
        } else {
            session_start();
			return (int)session_id();
        }
    }

    public function get_id() {
        return empty($_COOKIE['pfm_session_id']) ? 0 : $_COOKIE['pfm_session_id'];
    }

    /**
     * @param $name
     * @param bool|false $default
     * @return bool
     * @throws InvalidArgumentException
     */
    public function get_var($name, $default = false) {
        /**
         * @var $system System
         */
        $system = \Application::get_class('System');
        if($system->get_use_db_param()) {
            return $this->model->get_var($name, $default);
        } else {
            return (empty($_SESSION[$name])) ? $default : $_SESSION[$name];
        }

    }

    /**
     * @param $name
     * @param $value
     * @throws InvalidArgumentException
     */
    public function set_var($name, $value) {
        /**
         * @var $system System
         */
        $system = \Application::get_class('System');
        if($system->get_use_db_param()) {
            $this->model->set_var($name, $value);
        } else {
            $_SESSION[$name] = $value;
        }
    }

    /**
     * @param $name
     * @throws InvalidArgumentException
     */
    public function unset_var($name) {
        /**
         * @var $system System
         */
        $system = \Application::get_class('System');
        if($system->get_use_db_param()) {
            $this->model->unset_var($name);
        } else {
            unset($_SESSION[$name]);
        }
    }

    /**
     * @param $uid
     * @throws InvalidArgumentException
     */
    public function set_uid($uid) {
        /**
         * @var $system System
         */
        $system = \Application::get_class('System');
        if($system->get_use_db_param()) {
            $this->model->set_uid($uid);
        }
    }
}