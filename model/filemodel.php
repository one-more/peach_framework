<?php

abstract class FileModel {
	use TraitJSON;

	protected $file;
	protected $data;

	public function __construct() {
		$this->data = json_decode(file_get_contents($this->get_file()), true);
        if(!is_array($this->data)) {
            $this->data = [];
        }
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
        $record = array_filter($this->data, function($record) use($id) {
            return $record['id'] == $id;
        });
        $new_record = array_merge_recursive((array)reset($record), $data);
        foreach($this->data as &$el) {
            if($el['id'] == $id) {
                $el = $new_record;
            }
        }
    }

    /**
     * @param $id
     */
    public function delete($id) {
        foreach(array_keys($this->data) as $key) {
            if($this->data[$key]['id'] == $id) {
                unset($this->data[$key]);
            }
        }
    }

    public function __destruct() {
        $this->save();
    }

	protected function save() {
		file_put_contents($this->get_file(), $this->array_to_json_string($this->data));
	}

	public abstract function get_file();
}