<?php

trait trait_extension {

    public static function load_extension_class($name) {
        $class_name = strtolower($name).'.php';

    }
}