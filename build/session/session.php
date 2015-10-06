<?php

class Session implements Extension {
    use TraitExtension;

    /**
     * @var $model \Session\model\SessionModel
     */
    private $model;

	public function __construct() {
        $this->register_autoload();
        $this->model = \Application::get_class('\Session\model\SessionModel');
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
            $user = $ext->get_identity();
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
        return empty($_COOKIE['pfm_session_id']) ? null : $_COOKIE['pfm_session_id'];
    }

    /**
     * @param $name
     * @param bool|false $default
     * @return mixed
     */
    public function get_var($name, $default = false) {
        if($this->get_id()) {
            return $this->model->select()->where(function($record) use($name) {
                return $record['id'] == $this->get_id() && !empty($record['variables'][$name]);
            })->firstOrDefault([
                'variables' => [
                    "{$name}" => $default
                ]
            ])['variables'][$name];
        } else {
            return $default;
        }
    }

    /**
     * @param $name
     * @param $value
     */
    public function set_var($name, $value) {
        if($this->get_id()) {
            $this->model->update($this->get_id(), [
                'variables' => [
                    "{$name}" => $value
                ]
            ]);
        }
    }

    /**
     * @param $name
     */
    public function unset_var($name) {
       if($this->get_id()) {
           $record = $this->model->select()->where(function($record) {
               return $record['id'] == $this->get_id();
           })->toArray();
           if(!empty($record['variables'][$name])) {
               unset($record['variables'][$name]);
               $this->model->update($this->get_id(), $record);
           }
       }
    }

    /**
     * @param $uid
     */
    public function set_uid($uid) {
        if($this->get_id()) {
            $this->model->update($this->get_id(), [
                'uid' => $uid
            ]);
        }
    }
}
