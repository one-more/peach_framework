<?php

class TemplatesController {
	use trait_controller;

	public function get_templates_list() {
		$templates_list_file = ROOT_PATH.DS.'resource'.DS.'tools_templates_list.json';
		if(file_exists($templates_list_file)) {
			return json_decode(file_get_contents($templates_list_file), true);
		} else {
			return [];
		}
	}
}