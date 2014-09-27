<?php

class UserModel extends SuperModel {

    public function login($login, $password, $remember) {
        $sql    = "
            SELECT * FROM `users` WHERE `login` = ? AND `password` = ?
        ";
        $sth    = $this->db->prepare($sql);
        $login  = VarHandler::clean_html($login);
        $password   = VarHandler::clean_html($password);
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
                setcookie('user', $result['remember_hash'], time()+3600*24*365*200);
            } else {
                $session->set_var('user', $result['remember_hash']);
            }
            $session->set_uid($result['id']);
        }
        return $result;
    }

    public function get_fields($uid = null) {
        if(!$uid) {
            $user   = Application::get_class('User');
            $uid    = $user->get_id();
        }
        $params = [];
        $params['where']    = "`id` = {$uid}";
        return $this->select('users', $params);
    }

    public function register($fields) {
        $params = [
            'fields'    => $fields
        ];
        return $this->insert('users', $params);
    }

    public function update_fields($fields, $uid = null) {
        if(!$uid) {
            $user   = Application::get_class('User');
            $uid    = $user->get_id();
        }
        $params = [
            'fields'    => $fields,
            'where' => '`id` = '.$uid
        ];
        $this->update('users', $params);
    }

    public function get_users() {
        return $this->select('users');
    }

    public function get_id() {
        if(!empty($_COOKIE['user'])) {
            $remember_hash  = $_COOKIE['user'];
        } else {
            $session    = Application::get_class('Session');
            $remember_hash  = $session->get_var('user');
        }
        $params = [
            'fields'    => [
                'remember_hash' => $remember_hash
            ]
        ];
        $result = $this->select('users', $params);
        return $result;
    }

    public function log_out() {
        if(!empty($_COOKIE['user'])) {
            setcookie('user', '', -1);
        } else {
            $session    = Application::get_class('Session');
            $session->unset_var('user');
        }
    }
}