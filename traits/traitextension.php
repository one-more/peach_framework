<?php

namespace traits;

use classes\Application;
use helpers\StringHelper;

trait TraitExtension {
	use TraitConfiguration;

    private $path;

    private $lang_path;

    public function get_path() {
        $extension  = strtolower(__CLASS__);
        return "pfmextension://{$extension}";
    }

    public function get_lang_path() {
        return $this->get_path().DS.'lang'.DS.CURRENT_LANG;
    }

    public static function load_extension_class($name) {
        $parts = explode('\\', $name);
        $class_name = strtolower(array_pop($parts)).'.php';
        $extension = reset($parts);

        if(Application::extension_exists($extension)) {
            $parts = array_map([StringHelper::class, 'camelcase_to_dash'], $parts);
            $parts = array_map('strtolower', $parts);
            $file = 'pfmextension://'.implode(DS, $parts).DS.$class_name;

            if(file_exists($file)) {
                require_once $file;
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

	protected function register_autoload() {
		spl_autoload_register([__CLASS__, 'load_extension_class']);
	}
}