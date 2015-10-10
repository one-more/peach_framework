<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

require_once realpath(dirname(__DIR__.'../')).'/resource/defines.php';
require_once ROOT_PATH.DS.'class'.DS.'application.php';
require_once ROOT_PATH.DS.'trait'.DS.'traitjson.php';
require_once ROOT_PATH.DS.'lib/Smarty/Smarty.class.php';
require_once ROOT_PATH.DS.'lib'.DS.'vendor'.DS.'autoload.php';

class JSON {
    use TraitJSON;
}

class TestsEnv {
	public static function initialize() {
		require_once ROOT_PATH.DS.'class'.DS.'autoloader.php';
		Autoloader::init_autoload();

        if(!in_array('pfmextension', stream_get_wrappers(), $strict = true)) {
            stream_wrapper_register('pfmextension', 'PFMExtensionWrapper');
        }

		$_SESSION = [];
		static::init_test_tables();

        /**
         * @var $lang_obj Language
         */
        $lang_obj = Application::get_class('Language');
		$current_lang = $lang_obj->get_language();
		define('CURRENT_LANG', $current_lang);

        $starter_autoload = new ReflectionMethod('Starter', 'register_autoload');
        $starter_autoload->setAccessible(true);
        $starter_autoload->invoke(new Starter());

        $sessions_file = 'pfmextension://session'.DS.'resource'.DS.'session_model.json';
        $sessions_json = file_get_contents($sessions_file);
        $sessions = json_decode($sessions_json, true);
        $sessions[] = [
            'id' => count($sessions)+1,
            'date' => date('Y-m-d'),
            'uid' => 0,
            'variables' => []
        ];
        file_put_contents($sessions_file, (new JSON())->array_to_json_string($sessions));
        $_COOKIE['pfm_session_id'] = count($sessions);
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

		$sql = 'create table if not exists tests_table (';
		$sql .= ' `id` serial primary key';
		$sql .= ', `field1` varchar(255) not null default ""';
		$sql .= ', `field2` bigint not null default 0';
		$sql .= ', `field3` enum("val1", "val2", "val3") default "val1"';
		$sql .= ')';
		$model->query($sql);

		$sql = 'create table if not exists tests_table2 (';
		$sql .= ' `id` serial primary key';
		$sql .= ', `field1` varchar(255) not null default ""';
		$sql .= ', `field2` bigint not null default 0';
		$sql .= ', `field3` enum("val1", "val2", "val3") default "val1"';
		$sql .= ')';
		$model->query($sql);
	}
}

TestsEnv::initialize();