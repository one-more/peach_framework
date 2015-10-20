<?php

namespace traits;

trait TraitConfiguration {
	use TraitJSON;

	private function get_base_path() {
        return ROOT_PATH;
    }

	protected function get_params($name = __CLASS__) {
		$name = strtolower($name);
		$path   = $this->get_base_path().DS.'resource'.DS."{$name}.json";
		if(file_exists($path)) {
			return json_decode(file_get_contents($path), true);
		} else {
			return [];
		}
	}

	protected function set_params(array $params, $name = __CLASS__) {
		$old_params = $this->get_params($name);
		$new_params = array_merge($old_params, $params);
		$this->save_params($new_params, $name);
	}

	protected function unset_param($param, $name = __CLASS__) {
		$params = $this->get_params($name);
		unset($params[$param]);
		$this->save_params($params, $name);
	}

    protected function save_params(array $params, $name = __CLASS__) {
        $name = strtolower($name);
        $params_str = $this->array_to_json_string($params);
        file_put_contents($this->get_base_path().DS.'resource'.DS."{$name}.json", $params_str);
    }
}