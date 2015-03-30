<?php

class Request {

	public static function is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public static function get_var($name, $filter = 'raw', $default = null) {
		$request_vars = array_merge($_REQUEST, $_COOKIE);
		if(empty($request_vars[$name])) {
            return $default;
        } else {
            return VarHandler::sanitize_var($request_vars[$name], $filter, $default);
        }
    }

	public static function uri() {
		return explode('?', $_SERVER['REQUEST_URI'])[0];
	}

	public static function uri_parts() {
		return array_values(array_filter(explode('/', static::uri())));
	}

	public static function search_params() {
		$uri_parts = explode('?', $_SERVER['REQUEST_URI']);
		if(!empty($uri_parts[1])) {
			$chinks = explode('&', $uri_parts[1]);
			$result = [];
			foreach($chinks as $chunk) {
				$parts = explode('=', $chunk);
				$result[$parts[0]] = empty($parts[1]) ? '' : $parts[1];
			}
			return $result;
		} else {
			return [];
		}
	}
}