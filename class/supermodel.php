<?php

class SuperModel {

    protected $db;

    public function __construct($dbname, $user, $pass, $dbtype = 'mysql', $host = 'localhost') {
        $system = Application::get_class('System');
        $params = $system->get_configuration();
        if(!$params['use_db']) {
            throw new Exception('could not create model: use db param is false');
        }

        $this->db = new PDO("{$dbtype}:{host}=localhost;dbname={$dbname}",$user, $pass);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function execute($sql) {
        $sth    = $this->db->query($sql);
        if($sth->columnCount()) {
            return $sth->fetchAll();
        }
    }

    public function select($table, $params = []) {
        if(!empty($params['fields'])) {
            $fields = implode(',', $params['fields']);
        } else {
            $fields = '*';
        }
        $sql    = "SELECT {$fields} FROM `{$table}`";
        if(!empty($params['where'])) {
            $where  = $params['where'];
            $sql    .= " WHERE {$where}";
        }
        if(!empty($params['limit'])) {
            $limit  = $params['limit'];
            $sql    .= "LIMIT {$limit}";
        }
        $sth    = $this->db->query($sql);
        $sth    = $sth->fetchAll();
        if(count($sth) > 1) {
            return $sth;
        } else {
            $result = $sth[0];
            if(count($result) == 1) {
                $key   = array_keys($result)[0];
                return $result[$key];
            } else {
                return $result;
            }
        }
    }

    public function insert($table, $params = []) {
        if(!empty($params['fields'])) {
            $fields = '';
            foreach($params['fields'] as $k=>$v) {
                if(!empty($fields)) {
                    $fields .= ',';
                }
                $fields .= "`{$k}` = ? ";
            }
            $sql    = "INSERT INTO `{$table}` SET {$fields}";
            $sth    = $this->db->prepare($sql);
            $i  = 1;
            foreach($params['fields'] as &$el) {
                $sth->bindParam($i, $el);
                $i++;
            }
            $sth->execute();
            return $this->db->lastInsertId();
        } else {
            throw new Exception('fields are empty');
        }
    }

    public function update($table, $params) {
        if(!empty($params['fields'])) {
            $fields = '';
            foreach($params['fields'] as $k=>$v) {
                if(!empty($fields)) {
                    $fields .= ',';
                }
                $fields .= "`{$k}` = ? ";
            }
            $sql    = "UPDATE `{$table}` SET {$fields}";
            if(!empty($params['where'])) {
                $where  = $params['where'];
                $sql    .= " WHERE {$where}";
            }
            $sth    = $this->db->prepare($sql);
            $i  = 1;
            foreach($params['fields'] as &$el) {
                $sth->bindParam($i, $el);
                $i++;
            }
            $sth->execute();
        } else {
            throw new Exception('fields are empty');
        }
    }
}