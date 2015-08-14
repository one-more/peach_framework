<?php

class AnnotationsDecorator {
    private $object;

    public function __construct(StdClass $object) {
        $this->object = $object;
    }

    public function __call($method, $arguments) {
        return call_user_func_array([$this->object, $method], $arguments);
    }
}