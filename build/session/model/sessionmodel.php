<?php

class SessionModel extends SuperModel {

	public function __construct($dbname, $user, $pass, $dbtype = 'mysql', $host = 'localhost') {
		parent::__construct($dbname, $user, $pass, $dbtype, $host);

		$system = Application::get_class('System');
		if(!$system->use_db()) {
			throw new InvalidDBParamException("use db param must be true");
		}
	}

    public function start_session() {
        $user   = Application::get_class('User');
        $uid    = $user->get_id();
        $fields = [
            'fields'    => [
                'datetime'  => date('Y-m-d H:i:s'),
                'uid'   => $uid
            ]
        ];
        return $this->insert('session', $fields);
    }

    protected function get_vars() {
        $session    = Application::get_class('Session');
        $params = [
            'fields'    => [
                'vars'
            ],
            'where' => '`id` = '.$session->get_id()
        ];
        $vars   = $this->select('session', $params);
        return empty($vars) ? [] : json_decode($vars, true);
    }

    public function get_var($name, $default = false) {
        $vars   = $this->get_vars();
        return empty($vars[$name]) ? $default : $vars[$name];
    }

    public function set_var($name, $value) {
        $session    = Application::get_class('Session');
        $vars   = $this->get_vars();
        $vars[$name]    = $value;
        $params = [
            'fields'    => [
                'vars'  => json_encode($vars)
            ],
            'where' => '`id` = '.$session->get_id()
        ];
        $this->update('session', $params);
    }

    public function unset_var($name) {
        $session    = Application::get_class('Session');
        $vars   = $this->get_vars();
        if(isset($vars[$name])) {
            unset($vars[$name]);
            $params = [
                'fields'    => [
                    'vars'  => json_encode($vars)
                ],
                'where' => '`id` = '.$session->get_id()
            ];
            $this->update('session', $params);
        }
    }

    public function set_uid($uid) {
        $session    = Application::get_class('Session');
        $params = [
            'fields'    => [
                'uid'   => (int)$uid
            ],
            'where' => '`id` = '.$session->get_id()
        ];
        $this->update('session', $params);
    }

	public function get_uid() {
		$session    = Application::get_class('Session');
		$params = [
			'fields' => ['uid'],
			'where' => '`id` = '.$session->get_id()
		];
		return $this->select('session', $params);
	}
}