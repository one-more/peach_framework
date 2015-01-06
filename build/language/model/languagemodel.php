<?php

class LanguageModel extends SuperModel {

    public function initialize() {
        $sql_path   = 'phar://'.ROOT_PATH.DS.'extensions'.DS.'language.tar.gz'.DS.'resource'.DS.'initialize.sql';
        $sql    = file_get_contents($sql_path);
        $sql_chunks = explode("\n\n", $sql);
        foreach($sql_chunks as $el) {
            if(!empty(trim($el))) {
                $this->execute($el);
            }
        }
    }

    public function set_var($key, $value, $page) {
        $params = [
            'fields'    => [
                'page'  => $page,
                'var_key'   => $key,
                'var_value' => $value,
                'lang'  => $this->current_language
            ]
        ];
        $this->insert('language_vars', $params);
    }

    public function get_var($name, $default) {
        $params = [
            'where' => "`var_key` = '{$name}' AND `lang` = '{$this->current_language}'"
        ];
        $result = $this->select('language_vars', $params);
        return empty($result) ? $default : $result['var_value'];
    }

    public function unset_var($name) {
        $where  = "`var_key`    = '{$name}' AND `lang` = '{$this->current_language}'";
        $this->delete('language_vars', $where);
    }

    public function set_page($name, $variables) {
        $variables_strs = [];
        foreach($variables as $el) {
            $el = array_map(function($var){
                return "'{$var}'";
            }, $el);
            $variables_strs[]   = "('{$name}',".implode(',', $el).')';
        }
        $variables_str  = implode(',', $variables_strs);
        $sql    = "INSERT INTO `language_vars`(`page`,`var_key`,`var_value`,`lang`) VALUES{$variables_str}";
        $this->execute($sql);
    }

    public function get_page($page) {
        $params = [
            'where'    => "`page` = '{$page}' AND `lang` = '{$this->current_language}'"
        ];
        $rows = $this->select('language_vars', $params);
        if(count($rows)) {
            if(!empty($rows[0]) && is_array($rows[0])) {
                $result = [];
                foreach($rows as $el) {
                    $result[$el['var_key']] = $el['var_value'];
                }
            } else {
                $result = [$rows['var_key'] => $rows['var_value']];
            }
        } else {
            $result = [];
        }
        return $result;
    }

    public function unset_page($page) {
        $where  = "`page`    = '{$page}' AND `lang` = '{$this->current_language}'";
        $this->delete('language_vars', $where);
    }

    public function import_variables($array) {
        $import_strings = [];
        $quote_str  = function($value){
            return "'{$value}'";
        };
        foreach($array as $el) {
            $el = array_map($quote_str, $el);
            $import_strings[]   = '('.implode(',', $el).')';
        }
        $import_string  = implode(',', $import_strings);
        $sql    = "INSERT INTO `language_vars`(`page`,`var_key`,`var_value`,`lang`) VALUES{$import_string}";
        $this->execute($sql);
    }
}