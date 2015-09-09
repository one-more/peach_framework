<?php

class Response {

    public static function redirect($url, $code = 303) {
        header($url, true, $code);
    }
}