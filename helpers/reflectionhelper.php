<?php

namespace helpers;

class ReflectionHelper {
    public static function get_class_annotations($class) {
        $reflection = new \ReflectionClass($class);
        $doc = $reflection->getDocComment();
        return static::parse_doc_comment($doc);
    }

    public static function get_method_annotations($class, $method) {
        $reflection = new \ReflectionMethod($class, $method);
        $doc = $reflection->getDocComment();
        return static::parse_doc_comment($doc);
    }

    private static function parse_doc_comment($doc) {
        preg_match_all('#@(.*?)\n#s', $doc, $annotations);
        return array_map(function($el) {
            $gap_pos = strpos($el, ' ');
            $annotation['name'] = trim(substr($el, 0, $gap_pos));
            $annotation['value'] = trim(substr($el, $gap_pos));
            return $annotation;
        }, $annotations[1]);
    }
}