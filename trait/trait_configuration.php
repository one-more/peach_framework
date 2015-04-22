<?php

trait trait_configuration {
	use trait_json;

	protected function get_params($name = null) {
		if(!$name) {
			$name   = strtolower(__CLASS__);
		}
		$path   = ROOT_PATH.DS.'resource'.DS."{$name}.json";
		if(file_exists($path)) {
			return json_decode(file_get_contents($path), true);
		} else {
			return [];
		}
	}

	protected function set_params($name, $params) {
		$old_params = $this->get_params($name);
		$new_params = array_merge($old_params, $params);
		$params_str = $this->array_to_json_string($new_params);
		file_put_contents(ROOT_PATH.DS.'resource'.DS."{$name}.json", $params_str);
	}

	protected function unset_param($param, $name = null) {
		if(!$name) {
			$name   = strtolower(__CLASS__);
		}
		$params = $this->get_params($name);
		unset($params[$param]);
		$params_str = $this->array_to_json_string($params);
		file_put_contents(ROOT_PATH.DS.'resource'.DS."{$name}.json", $params_str);
	}
}