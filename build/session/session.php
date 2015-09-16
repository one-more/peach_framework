<?php

class Session implements Extension {
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
        if(!$this->get_id()) {
            /**
             * @var $ext User
             */
            $ext = Application::get_class('User');
            $user = $ext->get_current();
            return $this->model->insert([
                'date' => date('Y-m-d'),
                'uid' => $user->id,
                'variables' => []
            ]);
        } else {
            return $this->get_id();
        }
    }

    /**
     * @return int
     */
    public function get_id() {
        return empty($_COOKIE['pfm_session_id']) ? 0 : $_COOKIE['pfm_session_id'];
    }

    /**
     * @param $name
     * @param bool|false $default
     * @return mixed
     */
    public function get_var($name, $default = false) {
        return $this->model->select()->where(function($record) use($name) {
            return !empty($record['variables'][$name]);
        })->firstOrDefault($default);
    }

    /**
     * @param $name
     * @param $value
     */
    public function set_var($name, $value) {
        $this->model->update($this->get_id(), [
            'variables' => [
                "{$name}" => $value
            ]
        ]);
    }

    /**
     * @param $name
     */
    public function unset_var($name) {
        $record = (array)$this->model->select()->where('$id == '.$this->get_id());
        if(!empty($record['variables'][$name])) {
            unset($record['variables'][$name]);
            $this->model->update($this->get_id(), $record);
        }
    }

    /**
     * @param $uid
     */
    public function set_uid($uid) {
        $this->model->update($this->get_id(), [
            'uid' => $uid
        ]);
    }
}