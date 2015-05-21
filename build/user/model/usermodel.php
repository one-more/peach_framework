<?php

class UserModel extends SuperModel {
	private $cached_fields = [];
	private $cached_users = [];

    public function login($login, $password, $remember = false) {
        $sql    = "
            SELECT * FROM `users` WHERE `login` = ? AND `password` = ?
        ";
        $sth    = $this->db->prepare($sql);
        $login  = VarHandler::sanitize_var($login, 'string');
        $password   = VarHandler::sanitize_var($password, 'string');
        $sth->bindParam(1, $login, PDO::PARAM_STR);
        $sth->bindParam(2, $password, PDO::PARAM_STR);
        $sth->execute();
        $result = $sth->fetch();
        if(!empty($result)) {
            if(empty($result['remember_hash'])) {
                $remember_hash  = password_hash($result['id'].$result['login'], PASSWORD_DEFAULT);
                $result['remember_hash']    = $remember_hash;
                $this->update_fields(['remember_hash'   => $remember_hash], $result['id']);
            }
            $session    = Application::get_class('Session');
            if($remember) {
                setcookie('user', $result['remember_hash'], strtotime('2037-12-31'), '/');
            } else {
                $session->set_var('user', $result['remember_hash']);
            }
            $session->set_uid($result['id']);
        }
        return !empty($result) ? $result : [];
    }

    public function get_fields($uid = null) {
        if(!$uid) {
            $uid    = $this->get_id();
        } else {
			$uid = (int)$uid;
		}
		if(empty($this->cached_fields[$uid])) {
			$params = [];
			$params['where']    = "`id` = {$uid} and deleted = 0";
			$fields = $this->select('users', $params);
			$this->cached_fields[$uid] = $fields;
			return $fields;
		} else {
			return $this->cached_fields[$uid];
		}
    }

    public function register($fields) {
        $params = [
            'fields'    => $fields
        ];
        return $this->insert('users', $params);
    }

    public function update_fields($fields, $uid = null) {
        if(!$uid) {
            $uid    = $this->get_id();
        }
        $params = [
            'fields'    => $fields,
            'where' => '`id` = '.$uid
        ];
        $this->update('users', $params);
    }

    public function get_users($ids = null) {
		if($ids && is_array($ids)) {
			$ids = array_map('intval', $ids);
			$ids_str = implode(',', $ids);
			$key = md5($ids_str);
			$params = [
				'where' => ' id in ('.$ids_str.') and deleted = 0'
			];
		} else {
			$params = [
				'where' => ' deleted = 0'
			];
			$key = 'all';
		}
		if(!empty($this->cached_users[$key])) {
			return $this->cached_users[$key];
		} else {
			$users = $this->get_arrays('users', $params);
			$this->cached_users[$key] = $users;
			return $users;
		}
    }

    public function get_id() {
		if(!empty($_COOKIE['user'])) {
			$remember_hash  = $_COOKIE['user'];
		} else {
			$session    = Application::get_class('Session');
			$remember_hash  = $session->get_var('user');
		}
		if($remember_hash) {
			$params = [
				'where'    => "remember_hash = '{$remember_hash}'"
			];
			$result = $this->select('users', $params);
			return !empty($result['id']) ? $result['id'] : 0;
		} else {
			return 0;
		}
    }

    public function get_user_by_field($field, $value) {
        $sql    = "SELECT * FROM `users` WHERE `{$field}` = ? and deleted = 0";
        $sth    = $this->db->prepare($sql);
        $sth->bindParam(1, $value);
        $sth->execute();
        return $this->return_from_statement($sth);
    }

    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            setcookie('user', '', -1, '/');
        } else {
            $session    = Application::get_class('Session');
            $session->unset_var('user');
        }
    }
}