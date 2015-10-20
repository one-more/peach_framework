<?php

use Session\mappers\SessionMapper;

class Session implements \common\interfaces\Extension {
    use \common\traits\TraitExtension;

    /**
     * @var $model \Session\models\SessionModel
     */
    private $model;

    /**
     * @var $mapper \Session\mappers\SessionMapper
     */
    private $mapper;

	public function __construct() {
        $this->register_autoload();
        $this->mapper = \common\classes\Application::get_class(SessionMapper::class);
	}

    /**
     * @return int
     * @throws InvalidArgumentException
     */
    public function start() {
        if(!$this->get_id()) {
            $this->model = new \Session\models\SessionModel([
                'uid' => 0,
                'vars' => []
            ]);
            $this->mapper->save($this->model);
            setcookie('pfm_session_id', $this->model->id, null, '/');
            return $_COOKIE['pfm_session_id'] = $this->model->id;
        } else {
            if(!$this->model) {
                $this->model = $this->mapper->find_by_id($this->get_id());
            }
            return $this->get_id();
        }
    }

    /**
     * @return int
     */
    public function get_id() {
        return empty($_COOKIE['pfm_session_id']) ? null : $_COOKIE['pfm_session_id'];
    }

    /**
     * @param $name
     * @param bool|false $default
     * @return bool
     * @throws ErrorException
     */
    public function get_var($name, $default = false) {
        if(!$this->get_id()) {
            throw new ErrorException('Session was not start');
        }
        return !empty($this->model->vars[$name]) ?
            $this->model->vars[$name] : $default;
    }

    /**
     * @param $name
     * @param $value
     * @throws ErrorException
     */
    public function set_var($name, $value) {
        if(!$this->get_id()) {
            throw new ErrorException('Session was not start');
        }
        $this->model->vars[$name] = $value;
        $this->mapper->save($this->model);
    }

    /**
     * @param $name
     * @throws ErrorException
     */
    public function unset_var($name) {
        if(!$this->get_id()) {
            throw new ErrorException('Session was not start');
        }
        unset($this->model->vars[$name]);
        $this->mapper->save($this->model);
    }

    /**
     * @param $uid
     * @throws ErrorException
     */
    public function set_uid($uid) {
        if(!$this->get_id()) {
            throw new ErrorException('Session was not start');
        }
        $this->model->uid = $uid;
        $this->mapper->save($this->model);
    }
}
