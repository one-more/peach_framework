<?php

trait TraitIterator {
    private $position = 0;
    private $values = [];  

    public function __construct($values) {
        $this->position = 0;
        $this->values = $values;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function current() {
        return $this->values[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->values[$this->position]);
    }
}