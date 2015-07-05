<?php

abstract class MysqlModel {

	/**
	 * @var $db PDO
	 */
	protected $db;
	protected $current_language;
	protected $query;
	protected $bind_values = [];
	protected $statement;

	public function __construct() {
		/**
		 * @var $system System
		 */
		$system = Application::get_class('System');
        if(!$system->use_db()) {
            throw new InvalidDBParamException("could not create model: use db param is false");
        }
		$configuration = $this->get_configuration();
		$db_type = $configuration['db_type'];
		$host = $configuration['host'];
		$db_name = $configuration['db_name'];
		$db_user = $configuration['db_user'];
		$db_password = $configuration['db_password'];
		$this->db = new PDO("{$db_type}:host={$host};dbname={$db_name}",$db_user, $db_password);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->query('SET NAMES utf8');

		/**
		 * @var $language Language
		 */
		$language = Application::get_class('Language');
		$this->current_language = $language->get_language();
	}

	protected function get_configuration() {
		/**
		 * @var $system System
		 */
		$system = Application::get_class('System');
		$db_params = $system->get_configuration()['db_params'];
		return [
			'db_name' => $db_params['name'],
			'db_user' => $db_params['login'],
			'db_password' => $db_params['password'],
			'db_type' => 'mysql',
			'host' => 'localhost'
		];
	}

	protected function add_lang($table_name) {
		return $this->current_language.'_'.$table_name;
	}

	abstract protected function get_table();

	protected function select($fields = null) {
		$fields = (array)$fields;
		if(!empty($fields)) {
			$fields = implode(',', $fields);
		} else {
			$fields = '*';
		}
		$this->query = 'SELECT '.$fields.' FROM';
		$this->query .= ' '.$this->get_table();
		return $this;
	}

	protected function update($fields) {
		$fields = (array)$fields;
		$parts = [];
		if(empty($fields)) {
			throw new InvalidArgumentException('fields are empty!');
		}
		$this->query = 'UPDATE '.$this->get_table().' SET';
		foreach($fields as $column=>$val) {
			$parts[] = ' '.$column.' = ?';
			$this->bind_values[] = $val;
		}
		$this->query .= implode(',', $parts);
		return $this;
	}

	protected function insert($fields) {
		$fields = (array)$fields;
		$parts = [];
		if(empty($fields)) {
			throw new InvalidArgumentException('fields are empty!');
		}
		$this->query = 'INSERT INTO '.$this->get_table().' SET';
		foreach($fields as $column=>$val) {
			$parts[] = ' '.$column.' = ?';
			$this->bind_values[] = $val;
		}
		$this->query .= implode(',', $parts);
		return $this;
	}

	protected function delete() {
		$this->query = 'DELETE FROM '.$this->get_table();
		return $this;
	}

	/**
	 * @param $conditions - might be a string or array in format
	 * [
	 * 	'column name. might be also or || and operator' => 
	 * 		['operator', 'value', (optional boolean) prepare column]
	 * ]
	 */
	protected function where($conditions) {
		if(is_string($conditions)) {
			$this->query .= " WHERE {$conditions}";
		} else {
			$this->query .= ' WHERE ';
			$conditions = (array)$conditions;
			if(!empty($conditions)) {
				$this->parse_conditions($conditions);
			}
		}
		return $this;
	}

	protected function parse_conditions($conditions) {
		foreach($conditions as $col_name=>$condition) {
			if($col_name == 'or' || $col_name == 'and') {
				$this->parse_conditions($condition);
			} else {
				$this->query .= $col_name.' '.$condition[0];
				if(isset($condition[2]) && !$condition[2]) {
					$this->query .= ' '.$condition[1];
				} else {
					$this->query .= ' ?';
				}
			}
		}
	}
	
	protected function limit($num) {
		$num = (int)$num;
		$this->query .= " LIMIT {$num}";
		return $this;
	}
	
	protected function offset($num) {
		$num = (int)$num;
		$this->query .= " OFFSET {$num}";
		return $this;
	}
	
	protected function join($type, $table, $conditions) {
		$this->query .= ' '.strtoupper($type).' JOIN '.$table;
		$this->query .= ' ON';
		$this->parse_conditions($conditions);
		return $this;
	}

	protected function group_by($fields) {
		$fields = (array)$fields;
		if(!empty($fields)) {
			$fields = implode(',', $fields);
			$this->query .= " GROUP BY {$fields}";
		}
		return $this;
	}

	protected function order_by($fields) {
		$fields = (array)$fields;
		if(!empty($fields)) {
			$fields = implode(',', $fields);
			$this->query .= " ORDER BY {$fields}";
		}
		return $this;
	}

	protected function having($conditions) {
		if(is_string($conditions)) {
			$this->query .= " HAVING {$conditions}";
		} else {
			$this->query .= ' HAVING ';
			$conditions = (array)$conditions;
			if(!empty($conditions)) {
				$this->parse_conditions($conditions);
			}
		}
		return $this;
	}

	protected function execute($sql = null) {
		if($sql) {
			$this->statement = $this->db->query($sql);
		} else {
			$sth = $this->db->prepare($this->query);
			foreach($this->bind_values as $k=>$value) {
				$sth->bindParam($k+1, $value);
			}
			$sth->execute();
			$this->statement = $sth;
		}
		return $this;
	}

	protected function get_insert_id() {
		return $this->db->lastInsertId();
	}

	protected function get_arrays_from_statement(PDOStatement $sth) {
		$result = $this->return_from_statement($sth);
		return $this->data_to_arrays($result);
	}

	public function get_arrays() {
		return $this->get_arrays_from_statement($this->statement);
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

	protected function get_array_from_statement(PDOStatement $sth) {
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

	public function get_array() {
		$this->get_array_from_statement($this->statement);
	}

    protected function return_from_statement(PDOStatement $sth) {
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