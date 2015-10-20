<?php

namespace common\collections;

use common\interfaces\Collection;
use common\interfaces\Model;

class BaseCollection implements Collection {

    /**
     * @var $model_class Model
     */
    protected $model_class;

    protected $models = [];

    protected $position = 0;

    public function __construct($model_class) {
        $this->model_class = $model_class;
    }

    public function load(array $models) {
        $this->models = array_values($models);
    }

    public function get($id) {
        foreach($this as $model) {
            /**
             * @var $model Model
             */
            if($model->get_id() == $id) {
                return $model;
            }
        }
    }

    public function add(Model $model) {
        $this->models[] = $model;
    }

    public function count() {
        return count($this->models);
    }

    public function one() {
        return $this[0];
    }

    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return Model
     */
    public function current() {
        if(is_array($this->models[$this->position])) {
            $this->models[$this->position] = new $this->model_class($this->models[$this->position]);
        }
        return $this->models[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($this->models[$this->position]);
    }

    public function offsetSet($offset, $value) {
        $model_obj = new $this->model_class;
        if(!is_array($value) || !$value instanceof $model_obj) {
            throw new \InvalidArgumentException('value must be an array or '.get_class($model_obj));
        }
        if (is_null($offset)) {
            $this->models[] = $value;
        } else {
            $this->models[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->models[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->models[$offset]);
    }

    public function offsetGet($offset) {
        if(isset($this->models[$offset])) {
            if(is_array($this->models[$offset])) {
                $this->models[$offset] = new $this->model_class($this->models[$offset]);
            }
            return $this->models[$offset];
        } else {
            return null;
        }
    }

    public function to_array() {
        /**
         * @var $model Model
         */
        $model = new $this->model_class;
        return array_map(function($item) use($model) {
            if($item instanceof $model) {
                return $model->to_array();
            } else {
                return $item;
            }
        }, $this->models);
    }
}
