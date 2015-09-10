<?php

trait trait_file_uploader {
	private $destination_path;
	private $input_name;

	public function __construct($input_name) {
		$this->input_name = $input_name;
	}

	public function upload($destination_path) {
		$this->destination_path = $destination_path;

		if(!empty($_FILES[$this->input_name]) && is_array($_FILES[$this->input_name]['name'])) {
			return $this->upload_files();
		} elseif(!empty($_FILES[$this->input_name]['name'])) {
			return $this->upload_file();
		} else {
			throw new InvalidArgumentException('files are empty');
		}
	}

    /**
     * @return int - count of uploaded files
     * @throws Exception
     */
	private function upload_files() {
        $uploaded_count = 0;
		if(!empty($_FILES[$this->input_name]['name'][0])) {
			$files = count($_FILES[$this->input_name]['name']);
			for($i=0; $i<$files; $i++) {
				$tmp_file = $_FILES[$this->input_name]['tmp_name'][$i];
				$type = explode('/', $_FILES[$this->input_name]['type'][$i])[0];
				$extension = explode('.', $_FILES[$this->input_name]['name'][$i])[1];
				if(!empty($this->type) && ($type != $this->type)) {
					continue;
				}
				if(!empty($this->allowed_extensions) && !in_array($extension, $this->allowed_extensions)) {
					continue;
				}
				if(file_exists($tmp_file)) {
					if(!isset($this->rename_file) || $this->rename_file) {
						$new_file_name = md5_file($tmp_file);
					} else {
						$new_file_name = explode('.', $_FILES[$this->input_name]['name'][0])[0];
					}
					$new_file = $this->destination_path.DS."{$new_file_name}.{$extension}";
					if(!move_uploaded_file($tmp_file, $new_file)) {
						$message = 'could not upload file ';
						$message .= $tmp_file;
						throw new ErrorException($message);
					}
					@chmod($new_file, 0777);
                    $uploaded_count++;
				}
			}
		}
        return $uploaded_count;
	}

    /**
     * @return string - path to uploaded file
     * @throws Exception
     */
	private function upload_file() {
		$tmp_file = $_FILES[$this->input_name]['tmp_name'];
		$type = explode('/', $_FILES[$this->input_name]['type'])[0];
		$extension = explode('.', $_FILES[$this->input_name]['name'])[1];
		if(!empty($this->type) && ($type != $this->type)) {
			throw new InvalidArgumentException("forbidden file type: {$type}. must be {$this->type}");
		}
		if(!empty($this->allowed_extensions) && !in_array($extension, $this->allowed_extensions)) {
			throw new InvalidArgumentException('forbidden file extension');
		}
		if(!isset($this->rename_file) || $this->rename_file) {
			$new_file_name = md5_file($tmp_file);
		} else {
			$new_file_name = explode('.', $_FILES[$this->input_name]['name'])[0];
		}
		$new_file = $this->destination_path.DS."{$new_file_name}.{$extension}";
		if(!move_uploaded_file($tmp_file, $new_file)) {
			$message = 'could not upload file ';
			$message .= $tmp_file;
			throw new ErrorException($message);
		}
		@chmod($new_file, 0777);
		return $new_file;
	}
}