<?php

namespace User\model;

class UserModel extends \MysqlModel {

	protected function get_table() {
        return 'users';
    }

    /**
     * @param $login
     * @param $password
     * @param bool|false $remember
     * @throws \InvalidArgumentException
     * @return bool
     */
    public function login($login, $password, $remember = false) {
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
            $result = $this->add_remember_hash($result);
            /**
             * @var $session \Session
             */
            $session = \Application::get_class('Session');
            if($remember) {
                $_COOKIE['user'] = $result['remember_hash'];
                setcookie('user', $result['remember_hash'], strtotime('2037-12-31'), '/');
            } else {
                $session->set_var('user', $result['remember_hash']);
            }
            $session->set_uid($result['id']);
        }
        return !empty($result);
    }

    private function add_remember_hash(array $fields) {
        if(count($fields) && empty($fields['remember_hash']) && !empty($fields['id'])) {
            $remember_hash  = password_hash($fields['id'].$fields['login'], PASSWORD_DEFAULT);
            $this->update_fields(['remember_hash' => $remember_hash], $fields['id']);
        }
        return $fields;
    }

    /**
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            setcookie('user', '', -1, '/');
        } else {
            /**
             * @var $session \Session
             */
            $session = \Application::get_class('Session');
            $session->unset_var('user');
        }
        return true;
    }

    /**
     * @param null|int $uid
     * @return array
     * @throws \InvalidArgumentException
     */
    public function get_fields($uid = null) {
        if(!$uid) {
            $uid = $this->get_id();
        } else {
			$uid = (int)$uid;
		}
		$fields = $this->select()
            ->where([
                'id' => ['=', $uid],
                'and' => [
                    'deleted' => ['=', 0]
                ]
            ])
            ->execute()
            ->get_array();
        return $this->add_remember_hash($fields);
    }

    /**
     * @param $fields
     * @return int
     */
    public function register($fields) {
        return $this->insert($fields)
            ->execute()
            ->get_insert_id();
    }

    /**
     * @param $uid
     */
	public function delete_user($uid) {
        $this->update(['deleted' => 1])
            ->where(['id' => ['=', (int)$uid]])
            ->execute();
	}

    /**
     * @param $fields
     * @param null|int $uid
     * @throws \InvalidArgumentException
     */
    public function update_fields($fields, $uid = null) {
        if(!$uid) {
            $uid = $this->get_id();
        }
        $this->update($fields)
            ->where(['id' => ['=', (int)$uid]])
            ->execute();
    }

    /**
     * @param null|array $ids
     * @return array
     */
    public function get_users($ids = null) {
		if($ids && is_array($ids)) {
			$ids = array_map('intval', $ids);
			$ids_str = implode(',', $ids);
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
		}
		$result = $this->select()
            ->where($where)
            ->execute()
            ->get_arrays();
        return array_map(function($fields) {
            return $this->add_remember_hash($fields);
        }, $result);
    }

    /**
     * @throws \InvalidArgumentException
     * @return int
     */
    public function get_id() {
		if(!empty($_COOKIE['user'])) {
			$remember_hash = $_COOKIE['user'];
		} else {
            /**
             * @var $session \Session
             */
			$session = \Application::get_class('Session');
			$remember_hash = $session->get_var('user');
		}
		if($remember_hash) {
			$where = [
				'remember_hash' => ['=', $remember_hash]
			];
			$result = $this->select(['id'])
                ->where($where)
                ->execute()
                ->get_result();
			return !empty($result) ? (int)$result : 0;
		} else {
			return 0;
		}
    }

    /**
     * @param $field
     * @param $value
     * @return array
     * @throws \InvalidArgumentException
     */
    public function get_user_by_field($field, $value) {
		if(!is_string($field)) {
			throw new \InvalidArgumentException('field name must be a string');
		}
        $fields = $this->select()
            ->where([
                $field => ['=', $value],
                'and' => [
                    'deleted' => ['=', 0]
                ]
            ])
            ->execute()
            ->get_array();
        return $this->add_remember_hash($fields);
    }
}