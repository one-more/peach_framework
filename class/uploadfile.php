<?php

/**
 * Class UploadFile
 */
class UploadFile {

    /**
     * @var string $input_name
     */
    private $input_name;

    /**
     * @var int index
     */
    private $index;

    /**
     * @var string $type
     */
    public $type;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var int $size
     */
    public $size;

    /**
     * @var string $tmp_name
     */
    public $tmp_name;

    /**
     * @var int $error
     */
    public $error;

    /**
     * @var string $exception
     */
    public $extension;

    /**
     * @var string $uploaded_name
     */
    public $uploaded_name;

    public function __construct($input_name, $index = null) {
        $this->input_name = $input_name;
        $this->index = $index;
        $this->init();
    }

    private function init() {
        foreach(['name', 'type', 'size', 'error', 'tmp_name'] as $field) {
            if($this->index) {
                $this->{$field} = $_FILES[$this->input_name][$field][$this->index];
            } else {
                $this->{$field} = $_FILES[$this->input_name][$field];
            }
        }
        $this->extension = explode('.', $this->name)[1];
    }

    /**
     * @param $destination_dir
     * @param bool|true $rename
     * @return bool
     * @throws FileUploadingException
     */
    public function upload($destination_dir, $rename = true) {
        $this->check_errors();
        if($rename) {
            if($this->index) {
                $new_name = sha1_file($_FILES[$this->input_name]['tmp_name'][$this->index]);
            } else {
                $new_name = sha1_file($_FILES[$this->input_name]['tmp_name']);
            }
        } else {
            $new_name = $this->name;
        }
        $this->uploaded_name = "{$new_name}.{$this->extension}";
        $destination = $destination_dir.DS.$this->uploaded_name;
        if($this->index) {
            $tmp_file = $_FILES[$this->input_name]['tmp_name'][$this->index];
        } else {
            $tmp_file = $_FILES[$this->input_name]['tmp_name'];
        }
        return move_uploaded_file($tmp_file, $destination);
    }

    /**
     * @throws FileUploadingException
     */
    private function check_errors() {
        if($this->error != UPLOAD_ERR_OK) {
            throw new FileUploadingException($this->get_error_messages()[$this->error]);
        }
    }

    private function get_error_messages() {
        return [
            UPLOAD_ERR_INI_SIZE => 'File size is bigger than upload_max_filesize directive in php.ini',
            UPLOAD_ERR_FORM_SIZE => 'File size is bigger than MAX_FILE_SIZE value in form',
            UPLOAD_ERR_PARTIAL => 'File was received partially',
            UPLOAD_ERR_NO_FILE => 'File was not uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'No temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Some extension stopped file upload'
        ];
    }
}