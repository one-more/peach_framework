<?php

class ArrayHelper {

    public static function is_assoc_array($array) {
        if(!is_array($array)) {
            throw new InvalidArgumentException('argument is not array');
        }
        return (bool)count(array_filter(array_keys($array), 'is_string'));
    }
}