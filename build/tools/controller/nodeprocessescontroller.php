<?php

class NodeProcessesController {
	use trait_controller, trait_json;

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
		$file = ROOT_PATH.DS.'resource'.DS.'tools_node_processes_list.json';
		$processes = json_decode(file_get_contents($file), true);
		return array_map(function($el) {
			if(!is_bool($el['enabled'])) {
				$el['enabled'] = boolval($el['enabled'] == 'true');
			}
			return $el;
		}, $processes);
	}

	public function enable_process() {
		$process = Request::get_var('process', 'string');
		switch($process) {
			case 'static-builder':
				$static_builder = Application::get_class('StaticBuilderProcess');
				$static_builder->check();
				$this->update_process('static builder', ['enabled' => 'true']);
				break;
		}
	}

	public function disable_process() {
		$process = Request::get_var('process', 'string');
		switch($process) {
			case 'static-builder':
				$static_builder = Application::get_class('StaticBuilderProcess');
				$static_builder->kill();
				$this->update_process('static builder', ['enabled' => 'false']);
				break;
		}
	}

	private function update_process($process, $params) {
		$processes = $this->get_processes_list();
		foreach($processes as &$el) {
			if($el['name'] == $process) {
				$el = array_merge($el, $params);
			}
		}
		$this->save_processes_list($processes);
	}

	private function save_processes_list($processes) {
		$file = ROOT_PATH.DS.'resource'.DS.'tools_node_processes_list.json';
		$data = $this->array_to_json_string($processes);
		return file_put_contents($file, $data);
	}
}