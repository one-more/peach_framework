<?php

namespace Session\model;

use User\identity\UserIdentity;

class SessionModel extends \MysqlModel {

    protected function get_table() {
        return 'session';
    }

    public function start_session() {
        /**
         * @var $ext \User
         */
        $ext = \Application::get_class('User');
        /**
         * @var $user UserIdentity
         */
        $user = $ext->get_current();
        $fields = [
            'datetime' => date('Y-m-d H:i:s'),
            'uid' => $user->id
        ];
        return $this->insert($fields)
            ->execute()
            ->get_insert_id();
    }

    protected function get_vars() {
        /**
         * @var $session \Session
         */
        $session = \Application::get_class('Session');
        $vars = $this->select(['vars'])
            ->where(['id' => ['=', $session->get_id()]])
            ->execute()
            ->get_result();
        return empty($vars) ? [] : json_decode($vars, true);
    }

    public function get_var($name, $default = false) {
        $vars = $this->get_vars();
        return empty($vars[$name]) ? $default : $vars[$name];
    }

    public function set_var($name, $value) {
        /**
         * @var $session \Session
         */
        $session = \Application::get_class('Session');
        $vars = $this->get_vars();
        $vars[$name] = $value;
        $this->update(['vars' => json_encode($vars)])
            ->where(['id' => ['=', $session->get_id()]])
            ->execute();
    }

    public function unset_var($name) {
        /**
         * @var $session \Session
         */
        $session = \Application::get_class('Session');
        $vars = $this->get_vars();
        if(isset($vars[$name])) {
            unset($vars[$name]);
            $this->update(['vars' => json_encode($vars)])
                ->where(['id' => ['=', $session->get_id()]])
                ->execute();
        }
    }

    public function set_uid($uid) {
        /**
         * @var $session \Session
         */
        $session = \Application::get_class('Session');
        $this->update(['uid' => (int)$uid])
            ->where(['id' => ['=', $session->get_id()]])
            ->execute();
    }

	public function get_uid() {
        /**
         * @var $session \Session
         */
		$session = \Application::get_class('Session');
		return $this->select(['uid'])
            ->where(['id' => ['=', $session->get_id()]])
            ->execute()
            ->get_result();
	}
}