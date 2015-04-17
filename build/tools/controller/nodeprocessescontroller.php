<?php

class NodeProcessesController {
	use trait_controller;

	public function check_processes() {
		if($this->programs_installed(['node', 'gulp'])) {
			$processes = $this->get_processes_list();
			foreach($processes as $el) {
				if($el['enabled']) {
					$class = Application::get_class($el['class']);
					$class->check();
				}
			}
		}
	}

	private function program_installed($name) {
		return `which $name`;
	}

	private function programs_installed($names) {
		foreach($names as $el) {
			if(!$this->program_installed($el)) {
				return false;
			}
		}
		return true;
	}

	public function get_processes_list() {
		$file = 'pfmextension://tools/resource/node_processes_list.json';
		return json_decode(file_get_contents($file), true);
	}
}