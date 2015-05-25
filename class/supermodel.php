<?php

class SuperModel {

    protected $db;
	protected $current_language;

    public function __construct($dbname, $user, $pass, $dbtype = 'mysql', $host = 'localhost') {
        $system = Application::get_class('System');
        if(!$system->use_db()) {
            throw new Exception("could not create model: use db param is false");
        }

        $this->db = new PDO("{$dbtype}:{host}=localhost;dbname={$dbname}",$user, $pass);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->query('SET NAMES utf8');

		$language = Application::get_class('Language');
		$this->current_language = $language->get_language();
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
            $sql    .= " LIMIT {$limit}";
        }
		if(!empty($params['offset'])) {
			$offset = $params['offset'];
			$sql .= " OFFSET {$offset}";
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
            return (int)$this->db->lastInsertId();
        } else {
            throw new InvalidArgumentException('fields are empty');
        }
    }

    public function update($table, $params = []) {
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
            throw new InvalidArgumentException('fields are empty');
        }
    }

    public function delete($table, $where) {
        $sql    = "DELETE FROM `{$table}` WHERE {$where}";
        $this->execute($sql);
    }

	protected function get_arrays_from_statement($sth) {
		$result = $this->return_from_statement($sth);
		return $this->data_to_arrays($result);
	}

	public function get_arrays($table, $params = []) {
		$result = $this->select($table, $params);
		return $this->data_to_arrays($result);
	}

	protected function data_to_arrays($data) {
		if(is_array($data) && count($data)) {
			if(empty($data[0]) || count($data) == 1) {
				return [$data];
			} else {
				return $data;
			}
		} else {
			return [];
		}
	}

	protected function get_array_from_statement($sth) {
		$result = $this->return_from_statement($sth);
		return $this->data_to_array($result);
	}

	protected function data_to_array($data) {
		if(is_array($data)) {
			if(count($data) > 1 && isset($data[0])) {
				return $data[0];
			} else {
				return $data;
			}
		} else {
			return [];
		}
	}

	public function get_array($table, array $params = []) {
		$result = $this->select($table, $params);
		return $this->data_to_array($result);
	}

    protected function return_from_statement($sth) {
        if($sth->columnCount()) {
			if($data = $sth->fetch()) {
				$rows[] = $data;
				while($data = $sth->fetch()) {
					$rows[] = $data;
				}
				$sth = $rows;
			} else {
				return null;
			}
        } else {
            return null;
        }
		$return_from_array = function($array) use(&$return_from_array) {
			if(!is_array($array)) {
				return $array;
			} else {
				if(count($array) == 0) {
					return $array;
				} elseif (count($array) > 1) {
					return array_map($return_from_array, $array);
				} else {
					$result = array_values($array)[0];
					if(is_array($result)) {
						if(count($result) > 1) {
							return array_map($return_from_array, $result);
						} else {
							return array_values($result)[0];
						}
					} else {
						return $result;
					}
				}
			}
		};
		return $return_from_array($sth);
    }
}