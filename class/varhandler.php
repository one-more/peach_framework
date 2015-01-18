<?php

class VarHandler {

    public static function clean_html($var, $allowed_tags = '') {
        return strip_tags($var, $allowed_tags);
    }
}