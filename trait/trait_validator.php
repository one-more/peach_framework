<?php

trait trait_validator {
	protected function get_sanitized_vars($vars) {
		$result = [];
		foreach($vars as $var) {
			if(empty($var['name'])) {
				throw new Exception("filed name is empty in get_sanitized_vars method");
			}
			$type = isset($var['type']) ? $var['type'] : 'raw';
			$default = isset($var['default']) ? $var['default'] : '';
			$result[$var['name']] = Request::get_var($var['name'], $type, $default);
			if(is_string($result[$var['name']])) {
				$result[$var['name']] = trim($result[$var['name']]);
			}
			$clean_html = isset($var['clean_html']) ? $var['clean_html'] : true;
			$allowed_tags = isset($var['allowed_tags']) ? $var['allowed_tags'] : '';
			if($clean_html) {
				$result[$var['name']] = VarHandler::clean_html($result[$var['name']], $allowed_tags);
			}
			if(isset($var['required'])) {
				if(is_array($result[$var['name']])) {
					$is_empty = empty($result[$var['name']]) || empty($result[$var['name']][0]);
				} elseif(is_object($result[$var['name']])) {
					$object_fields = (array)$result[$var['name']];
					$is_empty = empty($object_fields) || empty($object_fields[0]);
				} else {
					$is_empty = empty($result[$var['name']]);
				}
				if($is_empty) {
					$msg = isset($var['error']) ? $var['error'] : "{$var['name']} is empty";
					throw new Exception($msg);
				}
			}
		}
		return $result;
	}
}