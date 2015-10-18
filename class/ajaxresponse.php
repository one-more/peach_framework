<?php

class AjaxResponse {

    public $status;
    public $errors;
    public $message;

    public function __toString() {
        return json_encode((array)$this);
    }
}