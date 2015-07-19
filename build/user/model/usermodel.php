<?php

class UserModel extends MysqlModel {
	private $cached_fields = [];
	private $cached_users = [];

	protected function get_table() {
        return 'users';
    }

    public function login($login, $password, $remember = false) {
        $login  = VarHandler::sanitize_var($login, 'string', '');
        $password   = VarHandler::sanitize_var($password, 'string', '');
        $result = $this->select()
            ->where([
                'login' => ['=', $login],
                'and' => [
                    'password' => ['=', $password]
                ]
            ])
            ->execute()
            ->get_array();
        if(!empty($result)) {
            if(empty($result['remember_hash'])) {
                $remember_hash  = password_hash($result['id'].$result['login'], PASSWORD_DEFAULT);
                $result['remember_hash']    = $remember_hash;
                $this->update_fields(['remember_hash'   => $remember_hash], $result['id']);
            }
            /**
             * @var $session Session
             */
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
			$fields = $this->select()
                ->where([
                    'id' => ['=', $uid],
                    'and' => [
                        'deleted' => ['=', 0]
                    ]
                ])
                ->execute()
                ->get_array();
			$this->cached_fields[$uid] = $fields;
			return $fields;
		} else {
			return $this->cached_fields[$uid];
		}
    }

    public function register($fields) {
		if(!is_array($fields) && !$fields instanceof Traversable) {
			throw new InvalidArgumentException('fields must be array or traversable object');
		}
		if(empty($fields['login'])) {
			throw new InvalidArgumentException('field login is empty');
		}
        if($this->get_user_by_field('login', $fields['login'])) {
			throw new LoginExistsException('such user already exists');
		}
        return $this->insert($fields)
            ->execute()
            ->get_insert_id();
    }

	public function delete_user($uid) {
        $this->update(['deleted' => 1])
            ->where(['id' => ['=', (int)$uid]])
            ->execute();
	}

    public function update_fields($fields, $uid = null) {
		if(!is_array($fields) && !$fields instanceof Traversable) {
			throw new InvalidArgumentException('fields must be array or traversable object');
		}
        if(!$uid) {
            $uid    = $this->get_id();
        }
        $this->update($fields)
            ->where(['id' => ['=', (int)$uid]])
            ->execute();
    }

    public function get_users($ids = null) {
		if($ids && is_array($ids)) {
			$ids = array_map('intval', $ids);
			$ids_str = implode(',', $ids);
			$key = md5($ids_str);
            $where = [
                'id' => ['in', "({$ids_str})", false],
                'and' => [
                    'deleted' => ['=', 0]
                ]
            ];
		} else {
			$where = [
				'deleted' => ['=', 0]
			];
			$key = 'all';
		}
		if(!empty($this->cached_users[$key])) {
			return $this->cached_users[$key];
		} else {
			$users = $this->select()
                ->where($where)
                ->execute()
                ->get_arrays();
			$this->cached_users[$key] = $users;
			return $users;
		}
    }

    public function get_id() {
		if(!empty($_COOKIE['user'])) {
			$remember_hash  = $_COOKIE['user'];
		} else {
            /**
             * @var $session Session
             */
			$session    = Application::get_class('Session');
			$remember_hash  = $session->get_var('user');
		}
		if($remember_hash) {
			$where = [
				'remember_hash'    => ['=', $remember_hash]
			];
			$result = $this->select()
                ->where($where)
                ->execute()
                ->get_result();
			return !empty($result['id']) ? $result['id'] : 0;
		} else {
			return 0;
		}
    }

    public function get_user_by_field($field, $value) {
		if(!is_string($value) || !is_string($field)) {
			throw new InvalidArgumentException('field name and value must be strings');
		}
        return $this->select()
            ->where([
                $field => ['=', $value],
                'and' => [
                    'deleted' => ['=', 0]
                ]
            ])
            ->execute()
            ->get_array();
    }

    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            setcookie('user', '', -1, '/');
        } else {
            /**
             * @var $session Session
             */
            $session    = Application::get_class('Session');
            $session->unset_var('user');
        }
    }
}