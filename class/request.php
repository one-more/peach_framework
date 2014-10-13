<?php

class Request {
    public static function is_ajax() {
        return !empty($_SERVER['QUERY_STRING']);
    }
}