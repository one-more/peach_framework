<?php

abstract class FileModel {
	use TraitJSON;

	protected $file;
	protected $data;

	public function __construct() {
		$this->data = json_decode(file_get_contents($this->get_file()), true);
	}

    /**
     * @param array $record
     * @return int
     */
    public function insert(array $record) {
        $record['id'] = count($this->data);
        $this->data[] = $record;
        return $record['id'];
    }

    /**
     * @return \YaLinqo\Enumerable
     */
	public function select() {
        return from($this->data);
    }

    /**
     * @param $id
     * @param array $data
     */
    public function update($id, array $data) {
        $new_record = array_merge_recursive($this->data[$id], $data);
        $this->data[$id] = $new_record;
    }

    /**
     * @param $id
     */
    public function delete($id) {
        if(!empty($this->data[$id])) {
            unset($this->data[$id]);
        }
    }

    public function __destruct() {
        $this->save();
    }

	protected function save() {
		file_put_contents($this->file, $this->array_to_json_string($this->data));
	}

	public abstract function get_file();
}