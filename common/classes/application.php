<?php

namespace common\classes;

use common\helpers\FileSystemHelper;
use common\helpers\ReflectionHelper;
use common\interfaces\Template;
use Validator\LIVR;

class Application {

    private static $instances = [];

    /**
     * @param $name
     * @param array $params
     * @return mixed
     * @throws \InvalidArgumentException
     */
    public static function get_class($name, array $params = []) {
        if(class_exists($name)) {
			if(!array_key_exists($name, static::$instances)) {
				$reflection = new \ReflectionClass($name);
				static::$instances[$name] = $reflection->newInstanceArgs($params);
			}
            $annotations = ReflectionHelper::get_class_annotations(static::$instances[$name]);
            if(count($annotations)) {
                $obj = static::handle_annotations(static::$instances[$name], $annotations);
                static::$instances[$name] = $obj;
            }
            return static::$instances[$name];
		} else {
			throw new \InvalidArgumentException("class {$name} does not exists");
		}
    }

    /**
     * @param $name
     * @return bool
     */
    public static function extension_exists($name) {
        $name = strtolower($name);
        $file = ROOT_PATH.DS.'extensions'.DS.$name.'.tar.gz';
        $build_dir = ROOT_PATH.DS.'build'.DS.$name;
        return trim($name) && (is_file($file) || is_dir($build_dir));
    }

    /**
     * @param $class
     * @param $annotations
     * @return object
     */
    private static function handle_annotations($class, $annotations) {
        foreach($annotations as $annotation) {
            switch($annotation['name']) {
                case 'decorate':
                    $reflection = new \ReflectionClass($annotation['value']);
                    return $reflection->newInstance($class);
                break;
                default:
                    return $class;
            }
        }
        return $class;
    }

	public static function initialize() {
        if(defined('TESTS_ENV') || self::is_dev()) {
            ini_set('display_errors', 'on');
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 'off');
            error_reporting(0);
        }

		require_once ROOT_PATH.DS.'common'.DS.'classes'.DS.'autoloader.php';

        Autoloader::init_autoload();

		FileSystemHelper::init_dirs();

        /**
         * @var $system \System
         */
		$system = static::get_class(\System::class);
		$system->initialize();

        /**
         * @var $configuration Configuration
         */
        $configuration = Application::get_class(Configuration::class);
		$current_lang = $configuration->language;
		define('CURRENT_LANG', $current_lang);
	}

	public static function start() {
        /**
         * @var $system \System
         */
		$system = static::get_class('System');
		$port = $_SERVER['SERVER_PORT'];
		if($port == 8080 && self::is_dev()) {
            /**
             * @var $tools \Tools
             */
            $tools = self::get_class('Tools');
            $tools->route();
		} else {
			self::start_template($system->template);
		}
	}

    /**
     * @param Template $template
     */
	private static function start_template(Template $template) {
		$template->route();
	}

	public static function is_dev() {
		if(!array_key_exists('REMOTE_ADDR', $_SERVER) || !array_key_exists('HTTP_HOST', $_SERVER)) {
			return true;
		} else {
			return $_SERVER['REMOTE_ADDR'] == '127.0.0.1' ||
			strpos($_SERVER['HTTP_HOST'], 'dev') !== false;
		}
	}

	public static function init_validator() {
        require_once ROOT_PATH.DS.'lib'.DS.'Validator'.DS.'autoload.php';
        LIVR::defaultAutoTrim(true);
    }
}