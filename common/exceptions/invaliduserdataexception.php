<?php

class InvalidUserDataException extends Exception {

    public $errors = [];

    public function __construct(array $errors) {
        $this->errors = $errors;
        foreach($errors as $key=>$val) {
            $this->message .= "{$key}: {$val}".PHP_EOL;
        }

        parent::__construct();
    }
}