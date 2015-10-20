<?php

namespace traits;

use helpers\StringHelper;

trait TraitTemplate {

    public function get_path() {
        return ROOT_PATH.DS.'templates'.DS.strtolower(__CLASS__);
    }

    public function get_lang_path() {
        return $this->get_path().DS.'lang'.DS.CURRENT_LANG;
    }

    public static function load_template_class($name) {
        $parts = explode('\\', $name);
        $class_name = strtolower(array_pop($parts)).'.php';
        $parts = array_map([StringHelper::class, 'camelcase_to_dash'], $parts);
        $parts = array_map('strtolower', $parts);
        $file = ROOT_PATH.DS.'templates'.DS.implode(DS, $parts).DS.$class_name;

        if(file_exists($file)) {
            require_once $file;
            return true;
        } else {
            return false;
        }
    }

	protected function register_autoload() {
		spl_autoload_register([__CLASS__, 'load_template_class']);
	}
}