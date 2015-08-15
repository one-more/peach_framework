<?php

class JsonResponse implements AjaxResponse {

    public $blocks = [];
    public $views = [];
    public $title = '';
    public $status;
    public $message;

    public function offsetSet($offset, $value) {
        $this->$offset = $value;
    }

    public function offsetExists($offset) {
        return isset($this->$offset);
    }

    public function offsetUnset($offset) {
        unset($this->$offset);
    }

    public function &offsetGet($offset) {
        return $this->$offset;
    }

    public function __toString() {
        return json_encode((array)$this);
    }
}