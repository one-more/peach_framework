<?php

class InvalidUserDataException extends Exception {

    public $errors = [];

    public function __construct(array $errors) {
        $this->errors = $errors;

        parent::__construct();
    }
}