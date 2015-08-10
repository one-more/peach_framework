<?php

class StaticBuilderProcess {

	private $cwd;
	private $build_dir;

	public function __construct() {
		$this->cwd = ROOT_PATH.DS.'static_builder';
		$this->build_dir = WEB_ROOT;
		if(!$this->initialized()) {
			$this->build();
		}
	}

	private function initialized() {
		$dirs = glob($this->cwd.DS.'src'.DS.'*');
		foreach($dirs as $dir) {
			if(!is_dir($this->build_dir.DS.basename($dir))) {
				return false;
			}
		}
		return true;
	}

	public function build() {
		$this->run_cmd('build');
	}

	public function check() {
		if(!$this->get_pid()) {
			$this->run();
		}
	}

	private function get_pid() {
		$pids = trim(`ps aux | grep gulp | grep -v grep | awk '{print $2}'`);
		$pids = explode("\n", $pids);
		if(count($pids)) {
			foreach($pids as $pid) {
				$path = trim(`pwdx $pid | awk '{print $2}'`);
				if($path == $this->cwd) {
					//static builder is running
					return $pid;
				}
			}
			return null;
		} else {
			return null;
		}
	}

	public function run() {
		$this->run_cmd();
	}

	private function run_cmd($command = 'watch') {
		$error_path = WEB_ROOT.DS.'error.log';
		switch($command) {
			case 'watch':
				$cmd = "gulp > $error_path 2>&1 &";
				break;
			case 'build':
				$cmd = 'gulp build';
				break;
			case 'kill':
				$pid = $this->get_pid();
				if($pid) {
					`kill $pid`;
				}
				return;
				break;
		}
		$cwd = getcwd();
		chdir($this->cwd);
		`$cmd`;
		chdir($cwd);
	}

	public function kill() {
		$this->run_cmd('kill');
	}
}