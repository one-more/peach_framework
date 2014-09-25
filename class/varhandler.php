<?php

class VarHandler {

    public static function clean_html($var) {
        return trim(htmlentities($var));
    }
}