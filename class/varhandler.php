<?php

class VarHandler {

    public static function clean_html($var, $allowed_tags = '') {
		$var = htmlspecialchars_decode($var);
		$var = html_entity_decode($var);
        $result = strip_tags($var, $allowed_tags);
		return $result;
    }
	
	public static function sanitize_var($var, $filter, $default = '') {
		switch($filter) {
                case 'email':
                    $result = filter_var($var, FILTER_SANITIZE_EMAIL);
					break;
                case 'float':
                    $result = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					break;
                case 'int':
                    $result = filter_var($var, FILTER_SANITIZE_NUMBER_INT);
					break;
                case 'special_chars':
                    $result = filter_var($var, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
					break;
                case 'string':
                    $result = filter_var($var, FILTER_SANITIZE_STRING);
					break;
                case 'url':
                    $result = filter_var($var, FILTER_SANITIZE_URL);
					break;
                default:
                    $result = $var;
            }
		if(is_array($result)) {
			$result = array_filter($result); //delete empty values
			return empty($result) ? $default : $result;
		} elseif(is_object($result)) {
			$result = get_object_vars($result);
			$result = array_filter($result); //delete empty values
			return empty($result) ? $default : $result;
		} else {
			return empty(trim($result)) ? $default : $result;
		}
	}
}