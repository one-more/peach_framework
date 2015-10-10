<?php

class FileAdapter {
	use TraitJSON;

	protected $file;
	protected $data;

	public function __construct($file) {
		if(is_file($file)) {
            $this->file = $file;
            $this->data = (array)json_decode(file_get_contents($file), true);
        } else {
            throw new InvalidArgumentException("{$file} does not exists");
        }
	}

    /**
     * @param array $record
     * @return int
     */
    public function insert(array $record) {
        $record['id'] = count($this->data)+1;
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
        $new_record = array_replace_recursive((array)reset($record), $data);
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
        Error::log(print_r($this->data, 1));
		file_put_contents($this->file, $this->array_to_json_string((array)$this->data));
	}
}
