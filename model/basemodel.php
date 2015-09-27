<?php

class BaseModel implements Model {

    protected $fields = [];

    /**
     * @param null|array $fields
     */
    public function __construct($fields = null) {
       if(is_array($fields) && count($fields)) {
           $this->fields = $fields;
       }
        $this->set_fields();
    }

    protected function set_fields() {
        foreach($this->fields as $name=>$val) {
            $this->{$name} = $val;
        }
    }

    public function get_id() {
        return empty($this->id) ? null : $this->id;
    }

    /**
     * @param array $data
     */
    public function load(array $data) {
        $this->fields = $data;
        $this->set_fields();
    }

    /**
     * @return array
     */
    public function to_array() {
        return $this->fields;
    }
}