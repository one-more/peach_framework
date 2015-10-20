<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'classes'.DS.'application.php';
require_once ROOT_PATH.DS.'traits'.DS.'traitjson.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';
require_once ROOT_PATH.DS.'lib'.DS.'vendor'.DS.'autoload.php';

class JSON {
    use \traits\TraitJSON;
}

class TestsEnv {
	public static function initialize() {
		require_once ROOT_PATH.DS.'classes'.DS.'autoloader.php';

		\classes\AutoLoader::init_autoload();

        require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'session.php';
        require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'mappers'.DS.'sessionmapper.php';

        if(!in_array('pfmextension', stream_get_wrappers(), $strict = true)) {
            stream_wrapper_register('pfmextension', \classes\PFMExtensionWrapper::class);
        }

		$_SESSION = [];
		static::init_test_tables();

        /**
         * @var $lang_obj \classes\Language
         */
        $lang_obj = \classes\Application::get_class(\classes\Language::class);
		$current_lang = $lang_obj->get_language();
		define('CURRENT_LANG', $current_lang);

        $starter_autoload = new ReflectionMethod('Starter', 'register_autoload');
        $starter_autoload->setAccessible(true);
        $starter_autoload->invoke(new Starter());

        $method = new ReflectionMethod(\classes\AutoLoader::class, 'is_extension_changed');
        $method->setAccessible(true);
        if($method->invoke(null, 'Session')) {
            $method = new ReflectionMethod(\classes\AutoLoader::class, 'build_extension');
            $method->setAccessible(true);
            $method->invoke(null, 'Session');
        }
        /**
         * @var $session Session
         */
        $session = \classes\Application::get_class(Session::class);
        $_COOKIE['pfm_session_id'] = $session->start();
	}

	private static function init_test_tables() {
		$configuration_file = ROOT_PATH.DS.'resource'.DS.'configuration.json';
		$params = json_decode(file_get_contents($configuration_file), true)['db_params'];

		$user = $params['login'];
		$pass = $params['password'];
		$model = new PDO("mysql:host=localhost;dbname={$params['name']}",$user, $pass);
		$model->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$model->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		$model->query('SET NAMES utf8');

		$sql = '
            CREATE TABLE IF NOT EXISTS tests_table (
              `id` serial primary key
              , `field1` varchar(255) not null default ""
              , `field2` bigint not null default 0
              , `field3` enum("val1", "val2", "val3") default "val1"
            )
        ';
		$model->query($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS tests_table2 (
              `id` serial primary key
              , `field1` varchar(255) not null default ""
              , `field2` bigint not null default 0
              , `field3` enum("val1", "val2", "val3") default "val1"
            )
        ';
		$model->query($sql);
	}
}

TestsEnv::initialize();