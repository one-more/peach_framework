<?php

namespace common\helpers;

class HTMLHelper {

    /**
     * @param $name
     * @param array $attributes
     * @return string
     */
    public static function tag($name, array $attributes) {
        return "<{$name} ".static::render_attributes($attributes)." /{$name}>";
    }

    /**
     * @param $name
     * @param array $attributes
     * @return string
     */
    public static function begin_tag($name, array $attributes) {
        return "<{$name} ".static::render_attributes($attributes).' >';
    }

    /**
     * @param $name
     * @return string
     */
    public static function end_tag($name) {
        return "</{$name}>";
    }

    /**
     * @param $content
     * @param bool|true $double_encode
     * @return string
     */
    private static function encode($content, $double_encode = true) {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $double_encode);
    }

    /**
     * @param $content
     * @return string
     */
    private static function decode($content) {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    /**
     * @param array $attributes
     * @return string
     */
    private static function render_attributes(array $attributes) {
        $html = '';
        foreach($attributes as $name => $value) {
            if(is_bool($value)) {
                if ($value) {
                    $html .= " {$name}";
                }
            } elseif(is_array($value)) {
                $value = json_encode($value);
                $html .= " {$name}=\"{$value}\"";
            } elseif($value !== null) {
                $value = static::encode($value);
                $html .= " {$name}=\"{$value}\"";
            }
        }

        return $html;
    }
}