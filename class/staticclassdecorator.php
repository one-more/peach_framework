<?php

class StaticClassDecorator {
    private $class_name;

    public function __construct($name) {
        $this->class_name = $name;
    }

    public function __call($method, $arguments) {
        $method = new ReflectionMethod($this->class_name, $method);
        if(is_callable($this->class_name, $method)) {
            return $method->invoke(null, $arguments);
        } else {
            $msg = "{$this->class_name} has no method {$method}";
            throw new InvalidArgumentException($msg);
        }
    }
}