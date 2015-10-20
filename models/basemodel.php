<?php

namespace models;

use exceptions\NotExistedFieldAccessException;
use interfaces\Model;

class BaseModel implements Model {

    /**
     * @param null|array $fields
     */
    public function __construct(array $fields = null) {
       if(count($fields)) {
           $this->load($fields);
       }
    }

    public function __set($field, $value) {
        if(array_key_exists($field, $this->fields)) {
            $this->fields[$field] = $value;
        } else {
            throw new NotExistedFieldAccessException(get_class($this)." has no field {$field}");
        }
    }

    public function &__get($field) {
        if(array_key_exists($field, $this->fields)) {
            return $this->fields[$field];
        } else {
            throw new NotExistedFieldAccessException(__CLASS__." has no field {$field}");
        }
    }

    public function set_field($name, $value) {
        $this->fields[$name] = $value;
    }

    public function get_id() {
        return $this->id;
    }

    /**
     * @param array $data
     */
    public function load(array $data) {
        $this->fields = array_replace_recursive($this->fields, $data);
    }

    /**
     * @return array
     */
    public function to_array() {
        return $this->fields;
    }
}
