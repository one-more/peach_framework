<?php

class StaticClassDecorator {
    private $class_name;

    public function __construct($name) {
        $this->class_name = $name;
    }

    public function __call($name, $arguments) {
        if(is_callable([$this->class_name, $name])) {
            $method = new ReflectionMethod($this->class_name, $name);
            return $method->invokeArgs(null, $arguments);
        } else {
            $msg = "{$this->class_name} has no method {$name}";
            throw new \exceptions\NotExistedMethodException($msg);
        }
    }
}