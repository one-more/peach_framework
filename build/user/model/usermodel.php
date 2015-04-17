<?php

class UserModel extends SuperModel {
	private $cached_fields = [];
	private $cached_users = [];

    public function login($login, $password, $remember) {
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
        return $result;
    }

    public function get_fields($uid = null) {
        if(!$uid) {
            $uid    = $this->get_id();
        } else {
			$uid = (int)$uid;
		}
		if(empty($this->cached_fields[$uid])) {
			$params = [];
			$params['where']    = "`id` = {$uid}";
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
				'where' => ' id in ('.$ids_str.')'
			];
		} else {
			$params = [];
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
        $user = Application::get_class('User');
		if($user->is_logined()) {
			if(!empty($_COOKIE['user'])) {
				$remember_hash  = $_COOKIE['user'];
			} else {
				$session    = Application::get_class('Session');
				$remember_hash  = $session->get_var('user');
			}
			$params = [
				'where'    => "remember_hash = '{$remember_hash}'"
			];
			$result = $this->select('users', $params);
			return $result['id'];
		} else {
			return -1;
		}
    }

    public function get_user_by_field($field, $value) {
        $sql    = "SELECT * FROM `users` WHERE `{$field}` = ?";
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

    public function add_unconfirmed_user($fields) {
        return  $this->insert('unconfirmed_users', ['fields'    => $fields]);
    }

    public function get_unconfirmed_user_by_field($field, $value) {
        $sql    = "SELECT * FROM `unconfirmed_users` WHERE `{$field}` = ?";
        $sth    = $this->db->prepare($sql);
        $sth->bindParam(1, $value);
        $sth->execute();
        return $sth->fetch();
    }

    public function purge_unconfirmed_users() {
        $records    = $this->select('unconfirmed_users');
        $purge_ids  = [];
        if(count($records)) {
            if(!empty($records[0])) {
                foreach($records as $el) {
                    $register_date  = $el['register_date'];
                    if((strtotime($register_date)+(60*60*24*5)) < time()) {
                        $purge_ids[]    = $el['id'];
                    }
                }
            } else {
                $register_date  = $records['register_date'];
                if((strtotime($register_date)+(60*60*24*5)) < time()) {
                    $purge_ids[]    = $records['id'];
                }
            }
        }
        if(count($purge_ids)) {
            $sql    = 'DELETE FROM `unconfirmed_users` WHERE id IN('.implode(',',$purge_ids).')';
            $this->execute($sql);
        }
    }

    public function delete_unconfirmed_user($id) {
        $sql    = 'DELETE FROM `unconfirmed_users` WHERE `id` = ?';
        $sth    = $this->db->prepare($sql);
        $sth->bindParam(1, $id, PDO::PARAM_INT);
        $sth->execute();
    }
}