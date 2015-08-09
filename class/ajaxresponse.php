<?php

class AjaxResponse implements ArrayAccess {

    public $blocks = [];
    public $views = [];
    public $title = '';

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
        return json_encode([
            'blocks' => $this->blocks,
            'views' => $this->views,
            'title' => $this->title
        ]);
    }
}