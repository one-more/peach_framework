<?php

class BaseCollection implements Collection {
    use TraitArrayAccess {
        TraitArrayAccess::__construct as trait_construct;
    };

    /**
     * @var $model_class Model
     */
    protected $model_class;

    protected $models = [];

    protected $position = 0;

    public function __construct($model_class) {
        if(class_exists($model_class)) {
            $this->model_class = $model_class;
        } else {
            throw new InvalidArgumentException("class {$model_class} does not exists");
        }
    }

    public function load(array $models) {
        $this->trait_construct($models);
        $this->models = $models;
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

    public function get_count() {
        return count($this->models);
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
}