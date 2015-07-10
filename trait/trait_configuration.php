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

	protected function set_params($params, $name = null) {
		$old_params = $this->get_params($name);
		$new_params = array_merge($old_params, $params);
		$this->save_params($name, $new_params);
	}

	protected function unset_param($param, $name = null) {
		$params = $this->get_params($name);
		unset($params[$param]);
		$this->save_params($name, $params);
	}

    protected function save_params($name = null, $params) {
        if(!$name) {
			$name   = strtolower(__CLASS__);
		}
        $params_str = $this->array_to_json_string($params);
        file_put_contents(ROOT_PATH.DS.'resource'.DS."{$name}.json", $params_str);
    }
}