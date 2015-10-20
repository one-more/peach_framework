<?php

namespace common\helpers;

class BEMHelper {

    public static function generate_attributes($name, $params) {
        $attributes = isset($params['attributes']) ? $params['attributes'] : [];
        $attributes['class'] = [$name];

        if (isset($params['mods']) && is_array($params['mods'])) {
            foreach ($params['mods'] as $key => $value) {
                if ($value !== false) {
                    $attributes['class'][] = $name . '_' . $key . ($value === true ? '' : '_' .$value);
                }
            }
        }

        if (isset($params['mix']) && is_array($params['mix'])) {
            $attributes['class'] = array_merge_recursive($attributes['class'], $params['mix']);
        }

        $attributes['class'] = implode(' ', $attributes['class']);

        $keys = array_keys($attributes);
        foreach($keys as $key) {
            $value = $attributes[$key];
            if (is_array($value)) {
                $attributes[$key] = json_encode($value);
            }
        }

        return $attributes;
    }


    public static function block($name = '', $content = '', array $params = [])
    {
        $singleTags = ['area', 'base', 'basefont', 'bgsound', 'br', 'col', 'command', 'embed', 'hr', 'img', 'input',
            'isindex', 'keygen', 'link', 'param', 'source', 'track', 'wbr'];

        $tag = isset($params['tag']) ? $params['tag'] : 'div';
        $attributes = self::generate_attributes($name, $params);

        $html = HTMLHelper::begin_tag($tag, $attributes);

        if (!in_array($tag, $singleTags, true)) {
            $html .= $content;
            $html .= HTMLHelper::end_tag($tag);
        }

        return $html;
    }
}