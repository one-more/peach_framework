<?php

class PFMExtensionWrapper {
	private $extensions_path;
    /**
     * @var $stream PharData
     */
    private $stream;
	private $extension;
	private $phar_file;
	private $phar_type = Phar::GZ;
	private $phar_dir;
	private $fp;
	private $pos = 0;

	public function __construct() {
		$this->extensions_path = ROOT_PATH.DS.'extensions';
	}

	private function parse_path($path) {
		$path = str_replace('pfmextension://', '', $path);
		$this->extension = explode('/', $path)[0];
		$this->phar_file = str_replace($this->extension.DS, '', $path);
		if(count($parts = explode('/', $this->phar_file)) > 1) {
			array_pop($parts);
			$this->phar_dir = DS.implode('/', $parts);
		}
		$this->extension .= '.tar.gz';
	}

	private function create_phar_data() {
		$extension_path = $this->extensions_path.DS.$this->extension;
		return new PharData($extension_path, 0, null, $this->phar_type);
	}

	public function stream_open($path, $mode, $options, &$opened_path) {
		$this->parse_path($path);
		$this->stream = $this->create_phar_data();
		if($this->phar_dir) {
			$this->stream->addEmptyDir($this->phar_dir);
		}
		//todo - change to work not only with rb mode
		if($mode == 'rb') {
			$file = 'phar://'.$this->extensions_path.DS.$this->extension.DS.$this->phar_file;
			if(file_exists($file)) {
				$this->fp = fopen($file, $mode);
			}
		}
		return true;
	}

	public function stream_write($data) {
		$this->pos += strlen($data);
		$this->stream->addFromString($this->phar_file, $data);
		return $this->pos;
	}

	public function stream_read($count) {
		return fread($this->fp, $count);
    }

    public function stream_tell() {
		return ftell($this->fp);
    }

    public function stream_eof() {
		return feof($this->fp);
    }

    public function stream_stat() {
		return fstat($this->fp);
    }

	public function url_stat($path, $flags) {
		$this->parse_path($path);
		$file_path = 'phar://'.$this->extensions_path.DS.$this->extension.DS.$this->phar_file;
		if($flags == STREAM_URL_STAT_LINK) {
			return lstat($file_path);
		}
		if($flags == STREAM_URL_STAT_QUIET) {
			return @stat($file_path);
		}
		return stat($file_path);
	}

	public function rename($old_path, $new_path) {
		$data = file_get_contents($old_path);
		unlink($old_path);
		file_put_contents($new_path, $data);
		return true;
	}

	public function unlink($path) {
		$this->parse_path($path);
		return unlink('phar://'.$this->extensions_path.DS.$this->extension.DS.$this->phar_file);
	}

	public function stream_metadata($path, $option, $value) {
		$this->parse_path($path);
		$file = 'phar://'.$this->extensions_path.DS.$this->extension.DS.$this->phar_file;
		if(is_array($value)) {
			$value = array_merge([$file], $value);
		} else {
			$value = array_merge([$file], [$value]);
		}
		$phar_data = $this->create_phar_data();
		switch($option) {
			case STREAM_META_TOUCH:
				if(!file_exists($file)) {
					$phar_data->addFromString($this->phar_file, '');
				}
				return call_user_func_array('touch', $value);
			case STREAM_META_OWNER_NAME:
				return false;
				//return call_user_func_array('chown', $value);
			case STREAM_META_OWNER:
				return false;
				//return call_user_func_array('chown', $value);
			case STREAM_META_GROUP_NAME:
				return false;
				//return call_user_func_array('chgrp', $value);
			case STREAM_META_GROUP:
				return false;
				//return call_user_func_array('chgrp', $value);
			case STREAM_META_ACCESS:
				$phar_data[$this->phar_file]->chmod($value[1]);
				return $value[1] === (fileperms($file) & 0777);
			default:
                return false;
		}
	}

	public function mkdir($path, $mode, $options) {
		$this->parse_path($path);
		$phar_data = $this->create_phar_data();
		$phar_data->addEmptyDir($this->phar_file);
		return true;
	}

	public function rmdir($path, $options) {
		$this->parse_path($path);
		$phar_data = $this->create_phar_data();
		$phar_data->delete($this->phar_file);
		return true;
	}
}