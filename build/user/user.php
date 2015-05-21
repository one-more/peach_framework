<?php

class User {
    use trait_extension;

    public function __construct() {
        $this->register_autoload();
    }

    public function is_logined() {
        return $this->get_id() > 0;
    }

    public function get_id() {
		$model  = $this->get_model('UserModel');
		return $model->get_id();
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
        $result = $this->get_fields($uid);
		return count($result) ? $result[$name] : '';
    }

    public function register($fields) {
        $model  = $this->get_model('UserModel');
        return $model->register($fields);
    }

    public function update_fields($fields, $uid = null) {
        $model  = $this->get_model('UserModel');
        $model->update_fields($fields, $uid);
    }

    public function get_users($ids = null) {
        $model  = $this->get_model('UserModel');
        return $model->get_users($ids);
    }

	public function get_users_field($field, $ids = null) {
		$model  = $this->get_model('UserModel');
        return array_map(function($el) use($field) {
			return $el[$field];
		}, $model->get_users($ids));
	}

    public function get_user_by_field($field, $value) {
        $model  = $this->get_model('UserModel');
        return $model->get_user_by_field($field, $value);
    }

    public function log_out() {
        $model  = $this->get_model('UserModel');
        $model->log_out();
    }
}