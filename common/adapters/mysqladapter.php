<?php

namespace common\adapters;

use common\classes\Application;
use common\classes\Error;
use common\traits\TraitConfiguration;
use common\classes\Configuration;

class MysqlAdapter {

    /**
     * @var $db \PDO
     */
    protected $db;
    protected $query;
    protected $bind_values = [];
    protected $statement;
    protected $table_name;

    public function __construct($table_name) {
        $this->table_name = $table_name;

        $configuration = $this->get_configuration();
        $db_type = $configuration['db_type'];
        $host = $configuration['host'];
        $db_name = $configuration['db_name'];
        $db_user = $configuration['db_user'];
        $db_password = $configuration['db_password'];
        $this->db = new \PDO("{$db_type}:host={$host};dbname={$db_name}",$db_user, $db_password);
        $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        $this->db->query('SET NAMES utf8');
    }

    protected function get_configuration() {
        /**
         * @var $configuration Configuration
         */
        $configuration = Application::get_class(Configuration::class);
        $db_params = $configuration->db_params;
        return array_merge([
            'db_type' => 'mysql',
            'host' => 'localhost'
        ], $db_params);
    }

    /**
     * @param null|array|string $fields
     * @return $this
     */
    public function select(array $fields = null) {
        $this->bind_values = [];

        if(count($fields)) {
            $fields = implode(',', (array)$fields);
        } else {
            $fields = '*';
        }
        $this->query = 'SELECT '.$fields.' FROM';
        $this->query .= ' '.$this->table_name;
        return $this;
    }

    /**
     * @param $alias
     * @return $this
     */
    public function alias($alias) {
        $this->query .= " AS {$alias}";
        return $this;
    }

    public function update(array $fields) {
        $this->bind_values = [];

        $parts = [];
        if(!count($fields)) {
            throw new \InvalidArgumentException('fields are empty!');
        }
        $this->query = 'UPDATE '.$this->table_name.' SET';
        foreach($fields as $column=>$val) {
            $parts[] = ' '.$column.' = ?';
            $this->bind_values[] = $val;
        }
        $this->query .= implode(',', $parts);
        return $this;
    }

    public function insert(array $fields) {
        $this->bind_values = [];

        $parts = [];
        if(!count($fields)) {
            throw new \InvalidArgumentException('fields are empty!');
        }
        $this->query = "INSERT INTO {$this->table_name} SET";
        foreach($fields as $column=>$val) {
            $parts[] = ' '.$column.' = ?';
            $this->bind_values[] = $val;
        }
        $this->query .= implode(',', $parts);
        return $this;
    }

    /**
     * @return $this
     */
    public function delete() {
        $this->bind_values = [];

        $this->query = "DELETE FROM {$this->table_name}";
        return $this;
    }

    /**
     * @param $conditions - might be a string or array in format
     * [
     * 	'column name. might be also or || and operator' =>
     * 		['operator', 'value', (optional boolean) prepare column]
     * ]
     * @return $this
     */
    public function where($conditions) {
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
            $col_name = strtolower($col_name);
            if($col_name === 'or' || $col_name === 'and') {
                foreach($condition as $sub_col_name => $sub_condition) {
                    $this->query .= ' '.$col_name;
                    $this->parse_conditions([$sub_col_name => $sub_condition]);
                }
            } else {
                $this->query .= ' '.$col_name.' '.$condition[0];
                if(isset($condition[2]) && !$condition[2]) {
                    $this->query .= ' '.$condition[1];
                } else {
                    $this->query .= ' ?';
                    $this->bind_values[] = $condition[1];
                }
            }
        }
    }

    public function limit($num) {
        $num = (int)$num;
        $this->query .= " LIMIT {$num}";
        return $this;
    }

    public function offset($num) {
        $num = (int)$num;
        $this->query .= " OFFSET {$num}";
        return $this;
    }

    public function join($type, $table, $conditions) {
        $this->query .= ' '.strtoupper($type).' JOIN '.$table;
        $this->query .= ' ON';
        $this->parse_conditions($conditions);
        return $this;
    }

    public function group_by($fields) {
        $fields = (array)$fields;
        if(!empty($fields)) {
            $fields = implode(',', $fields);
            $this->query .= " GROUP BY {$fields}";
        }
        return $this;
    }

    public function order_by($fields) {
        $fields = (array)$fields;
        if(!empty($fields)) {
            $fields = implode(',', $fields);
            $this->query .= " ORDER BY {$fields}";
        }
        return $this;
    }

    public function having($conditions) {
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

    public function execute($sql = null) {
        try {
            if($sql) {
                $this->statement = $this->db->query($sql);
            } else {
                $sth = $this->db->prepare($this->query);
                $i = 1;
                foreach($this->bind_values as $value) {
                    $sth->bindValue($i++, $value);
                }
                $sth->execute();
                $this->statement = $sth;
                $this->bind_values = [];
            }
        } catch (\PDOException $e) {
            Error::log($e->getMessage());
            Error::log($this->query);
        }
        return $this;
    }

    public function get_insert_id() {
        return (int)$this->db->lastInsertId();
    }

    private function get_arrays_from_statement(\PDOStatement $sth) {
        $result = $this->return_from_statement($sth);
        return $this->data_to_arrays($result);
    }

    public function get_arrays() {
        return $this->get_arrays_from_statement($this->statement);
    }

    private function data_to_arrays($data) {
        if(is_array($data) && count($data)) {
            if(empty($data[0]) || count($data) == 1) {
                return [$data];
            } else {
                return $data;
            }
        } elseif(!empty($data)) {
            return [[$data]];
        } else {
            return [];
        }
    }

    private function get_array_from_statement(\PDOStatement $sth) {
        $result = $this->return_from_statement($sth);
        return $this->data_to_array($result);
    }

    private function data_to_array($data) {
        if(is_array($data)) {
            if(isset($data[0]) && count($data) > 1) {
                return $data[0];
            } else {
                return $data;
            }
        } elseif(!empty($data)) {
            return [$data];
        } else {
            return [];
        }
    }

    public function get_array() {
        return $this->get_array_from_statement($this->statement);
    }

    private function return_from_statement(\PDOStatement $sth) {
        if($sth->columnCount()) {
            if($data = $sth->fetch()) {
                $rows[] = $data;
                while($data = $sth->fetch()) {
                    $rows[] = $data;
                }
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
                if (count($array) > 1) {
                    return array_map($return_from_array, $array);
                } else {
                    $result = reset($array);
                    if(is_array($result)) {
                        if(count($result) > 1) {
                            return array_map($return_from_array, $result);
                        } else {
                            return reset($result);
                        }
                    } else {
                        return $result;
                    }
                }
            }
        };
        return $return_from_array($rows);
    }

    public function get_result() {
        return $this->return_from_statement($this->statement);
    }
}