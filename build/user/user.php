<?php

class User {
    use trait_extension;

	private $model;

    public function __construct() {
        $this->register_autoload();
		$this->model = $this->get_model('UserModel');
    }

    public function is_logined() {
        return $this->get_id() > 0;
    }

    public function get_id() {
		return $this->model->get_id();
    }

    public function login($login, $password, $remember = false) {
        $result = $this->model->login($login, $password, $remember);
        return $result;
    }

    public function get_fields($uid = null) {
        return $this->model->get_fields($uid);
    }

    public function get_field($name, $uid = null) {
        $result = $this->get_fields($uid);
		return count($result) ? $result[$name] : '';
    }

    public function register($fields) {
        return $this->model->register($fields);
    }

	public function delete_user($uid) {
		$this->model->delete_user($uid);
	}

    public function update_fields($fields, $uid = null) {
		$this->model->update_fields($fields, $uid);
    }

    public function get_users($ids = null) {
        return $this->model->get_users($ids);
    }

	public function get_users_field($field, $ids = null) {
        return array_map(function($el) use($field) {
			return !empty($el[$field]) ? $el[$field] : '';
		}, $this->model->get_users($ids));
	}

    public function get_user_by_field($field, $value) {
        return $this->model->get_user_by_field($field, $value);
    }

    public function log_out() {
		$this->model->log_out();
    }
}
