<?php

abstract class FileModel {
	use TraitJSON;

	protected $file;
	protected $data;

	public function __construct() {
		$this->data = json_decode(file_get_contents($this->get_file()), true);
	}

	protected function save() {
		file_put_contents($this->file, $this->array_to_json_string($this->data));
	}

	abstract function get_file();
}