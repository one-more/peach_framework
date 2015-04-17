<?php

class StaticBuilderProcess {

	private $cwd;

	public function __construct() {
		$this->cwd = ROOT_PATH.DS.'static_builder';
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
		$cwd = getcwd();
		chdir($this->cwd);
		`gulp > /dev/null 2>&1 &`;
		chdir($cwd);
	}

	public function shutdown() {
		$pid = $this->get_pid();
		if($pid) {
			`kill $pid`;
		}
	}
}