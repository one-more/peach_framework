<?php

class Request {
    public static function is_ajax() {
        return !empty($_SERVER['QUERY_STRING']);
    }

    public static function get_var($name, $filter = 'raw', $default = null) {
        if(empty($_REQUEST[$name])) {
            return $default;
        } else {
            switch($filter) {
                case 'email':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_EMAIL);
                case 'float':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_NUMBER_FLOAT);
                case 'int':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_NUMBER_INT);
                case 'special_chars':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                case 'string':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_STRING);
                case 'url':
                    return filter_var($_REQUEST[$name], FILTER_SANITIZE_URL);
                default:
                    return filter_var($_REQUEST[$name], FILTER_UNSAFE_RAW);
            }
        }
    }
}