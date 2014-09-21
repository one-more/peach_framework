<?php

class UserModel extends SuperModel {

    public function login($login, $password) {
        $sql    = "
            SELECT * FROM `users` WHERE `login` = ? AND `password` = ?
        ";
        $sth    = $this->db->prepare($sql);
        $login  = VarHandler::clean_html($login);
        $password   = VarHandler::clean_html($password);
        $sth->bindParam(1, $login, PDO::PARAM_STR);
        $sth->bindParam(2, $password, PDO::PARAM_STR);
        $sth->execute();
        return $sth->fetch();
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
}