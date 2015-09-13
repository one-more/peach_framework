<?php

/**
 * Class UploadFiles
 */
class UploadFiles implements Iterator {

    /**
     * @var int $position
     */
    private $position = 0;

    /**
     * @var string $input_name;
     */
    private $input_name;

    /**
     * @param $input_name
     */
    public function __construct($input_name) {
        $this->input_name = $input_name;
    }

    public function rewind() {
        $this->position = 0;
    }

    /**
     * @return UploadFile
     */
    public function current() {
        return new UploadFile($this->input_name, $this->position);
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid() {
        return isset($_FILES[$this->input_name]['name'][$this->position]);
    }
}