<?php

class Templator {
    protected $path;
    protected $html;
    public function __construct($path) {
        if(!file_exists($path)) {
            throw new Exception("could not load template {$path}");
        }
        $this->path = $path;
        $this->load_template();
    }

    protected function load_template() {
        ob_start();
        include $this->path;
        $this->html = ob_get_clean();
        $this->html = preg_replace('/\s+/', ' ', $this->html);
    }

    public function replace_vars($params, $html = null) {
        foreach($params as $k=>$el) {
            if(is_array($el)) {
                $el = $this->replace_foreach($el['data'], $el['include']);
            }
            if($html) {
                if(preg_match('/^:.*:$/Usi', $el)) {
                    $template_var   = $el;
                    $el = $this->get_template_var($el, $html);
                    $html   = $this->unset_template_var($template_var, $html);
                }
                $html = preg_replace("/:{$k}:/", $el, $html);
            } else {
                if(preg_match('/^:.*:$/Usi', $el)) {
                    $template_var   = $el;
                    $el = $this->get_template_var($el);
                    $this->html = $this->unset_template_var($template_var);
                }
                $this->html = preg_replace("/:{$k}:/", $el, $this->html);
            }
        }
    }

    public function replace_if($params, $condition_field) {
        if($params[$condition_field]) {
            $this->replace_vars($params['true']);
        } else {
            $this->replace_vars($params['false']);
        }
    }

    public function replace_switch($params, $case) {
        foreach($params as $k=>$el) {
            if($k == $case) {
                $this->replace_vars($el);
            }
        }
    }

    public function get_template() {
        return $this->html;
    }

    public function __toString() {
        return $this->html;
    }

    protected function replace_foreach($params, $include) {
        $html   = '';
        foreach($params as $el) {
            if(is_array($el)) {
                ob_start();
                include($include);
                $tmp    = ob_get_clean();
                $html .= $this->replace_vars($el, $tmp);
            } else {
                ob_start();
                include($include);
                $tmp    = ob_get_clean();
                return $this->replace_vars($params, $tmp);
            }
        }
        return $html;
    }

    protected function get_template_var($name, $html = null) {
        $name   = str_replace(':', '', $name);
        if(!$html) {
            $html   = $this->html;
        }
        preg_match("/@{$name}{(.*)}/Uim", $html, $matches);
        return $matches[1];
    }

    protected function unset_template_var($name, $html = null) {
        $name   = str_replace(':', '', $name);
        if(!$html) {
            $html   = $this->html;
        }
        return preg_replace("/@{$name}{.*}/Uim", '', $html);
    }
}