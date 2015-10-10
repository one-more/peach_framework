<?php

class LanguageFile extends ArrayIterator {

    private $base_path;

    public function __construct($file, $base_dir = ROOT_PATH) {

        $this->base_path = $base_dir;

        $file_path = $this->base_path.DS.$file;
        if(file_exists($file_path)) {
            $this->values = json_decode(file_get_contents($file_path), true);
            parent::__construct($this->values);
        } else {
            throw new InvalidArgumentException("file {$file} does not exists");
        }
    }

    public function get_data() {
        return $this->values;
    }

    public function __toString() {
        return json_encode($this->values);
    }
}