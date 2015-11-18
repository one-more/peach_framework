<?php

namespace common\classes;

class AjaxResponse {

    public $status;
    public $errors;
    public $message;
    public $title;

    public function __toString() {
        return json_encode((array)$this);
    }
}