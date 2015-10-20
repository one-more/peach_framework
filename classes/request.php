<?php

namespace classes;

class Request {

	public static function is_ajax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
			strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

	public static function is_post() {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'post';
    }

    public static function is_get() {
        return strtolower($_SERVER['REQUEST_METHOD']) == 'get';
    }

    public static function get_var($name, $filter = 'raw', $default = null) {
		$request_vars = array_merge($_REQUEST, $_COOKIE);
		if(empty($request_vars[$name])) {
            return $default;
        } else {
			$var = $request_vars[$name];
			if(VarHandler::validate_var($var, $filter)) {
				return VarHandler::sanitize_var($var, $filter, $default);
			} else {
				return $default;
			}
        }
    }

	public static function uri() {
		$uri = empty($_SERVER['REQUEST_URI']) ? '' : $_SERVER['REQUEST_URI'];
		$result = explode('?', $uri)[0];
		if(substr($result, -1) === '/') {
            $result = substr($result, 0, -1);
        }
        return $result;
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

	public static function is_token_valid() {
		$client_token = self::get_var('token');
		unset($_GET['token']);
		$str_to_hash = self::get_var('user', null, '')
			.self::get_var('pfm_session_id', null, '')
			.json_encode(array_merge($_GET, $_POST), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		return md5($str_to_hash) == $client_token;
	}
}