<?php

class SuperModel {

    protected $db;

    public function __get($field) {
        switch($field) {
            case 'current_language':
                if(empty($this->$field)) {
                    $lang_obj   = Application::get_class('Language');
                    $this->$field   = $lang_obj->get_language();
                }
                break;
        }
        return $this->$field;
    }

    public function __construct($dbname, $user, $pass, $dbtype = 'mysql', $host = 'localhost') {
        $system = Application::get_class('System');
        if(!$system->use_db()) {
            throw new Exception("could not create model: use db param is false");
        }

        $this->db = new PDO("{$dbtype}:{host}=localhost;dbname={$dbname}",$user, $pass);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->query('SET NAMES utf8');
    }

    public function execute($sql) {
        $sth    = $this->db->query($sql);
        return $this->return_from_statement($sth);
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
        return $this->return_from_statement($sth);
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

    public function delete($table, $where) {
        $sql    = "DELETE FROM `{$table}` WHERE {$where}";
        $this->execute($sql);
    }

	public function get_arrays($table, $params = []) {
		$result = $this->select($table, $params);
		if(count($result) && empty($result[0])) {
			return [$result];
		}
		return $result;
	}

    protected function return_from_statement($sth) {
        if($sth->columnCount()) {
            $sth    = $sth->fetchAll();
        } else {
            $sth    = [];
        }
        if(count($sth) > 1 || count($sth) == 0) {
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
}