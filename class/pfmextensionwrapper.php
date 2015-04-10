<?php

class PFMExtensionWrapper {
	private $extensions_path;
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

	public function stream_open($path, $mode, $options, &$opened_path) {
		$this->parse_path($path);
		$extension_path = $this->extensions_path.DS.$this->extension;
		$this->stream = new PharData($extension_path, 0, null, $this->phar_type);
		if($this->phar_dir) {
			$this->stream->addEmptyDir($this->phar_dir);
		}
		if($mode == 'rb') {
			$this->fp = fopen('phar://'.$this->extensions_path.DS.$this->extension.DS.$this->phar_file, $mode);
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
			set_error_handler(function($errno, $errstr, $errfile, $errline) {
				if(strpos($errstr, 'stat') !== false) {
					return true;
				}
				return false;
			});
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
}