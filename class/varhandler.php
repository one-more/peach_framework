<?php

class VarHandler {

    public static function clean_html($var, $allowed_tags = '') {
		$var = htmlspecialchars_decode($var);
		$var = html_entity_decode($var);
        $result = strip_tags($var, $allowed_tags);
		return $result;
    }

	/**
	 * @param $var
	 * @param string $filter
	 * @return bool
	 */
	public static function validate_var($var, $filter = 'raw') {
		if(!$filter) {
			$filter = 'raw';
		}
		switch($filter) {
			case 'email':
				return (bool)filter_var($var, FILTER_VALIDATE_EMAIL);
			case 'float':
				return (bool)filter_var($var, FILTER_VALIDATE_FLOAT);
			case 'boolean':
				return (bool)$var;
			case 'int':
				if(is_array($var) || is_object($var) || is_resource($var)) {
					return false;
				} else {
					return (bool)filter_var((int)$var, FILTER_VALIDATE_INT);
				}
			case 'special_chars':
				return true;
			case 'string':
				return is_string($var);
			case 'url':
				if(!is_string($var)) {
					return false;
				}
				if(strpos($var, '?') !== false) {
					$parts = explode('?', $var);
					if(count($parts) > 2) {
						return false;
					}
					$url = $parts[0];
				} else {
					$url = $var;
				}
				$reg_host = '/^(\w*:)?([\w,#,\/,\d,\.,:]+)\.(\S+)$/uU';
				if(preg_match($reg_host, trim($url))) {
					return true;
				} else {
					return false;
				}
			case 'raw':
				return true;
			default:
				return false;
		}
	}

	/**
	 * @param $var
	 * @param string $filter
	 * @param null $default
	 * @return array|int|mixed|null|string
	 */
	public static function sanitize_var($var, $filter = 'raw', $default = null) {
		switch($filter) {
                case 'email':
                    $result = filter_var($var, FILTER_SANITIZE_EMAIL);
					break;
                case 'float':
                    $result = filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
					break;
                case 'int':
					if(is_array($var) || is_object($var) || is_resource($var)) {
						$result = $default;
					} else {
						$result = (int)$var;
					}
					break;
                case 'special_chars':
                    if(is_array($var) || is_object($var) || is_resource($var)) {
						$result = $default;
					} else {
						$result = htmlspecialchars($var, ENT_QUOTES);
					}
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