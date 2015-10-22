<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

defined('TESTS_ENV') or define('TESTS_ENV', true);

use common\classes\Application;
use common\classes\Configuration;

require_once '../resource/defines.php';
require_once ROOT_PATH.DS.'common'.DS.'classes'.DS.'application.php';
require_once ROOT_PATH.DS.'common'.DS.'traits'.DS.'traitjson.php';
require_once ROOT_PATH.DS.'lib'.DS.'Smarty'.DS.'Smarty.class.php';
require_once ROOT_PATH.DS.'lib'.DS.'Validator'.DS.'autoload.php';

class JSON {
    use common\traits\TraitJSON;
}

class TestsEnv {
	public static function initialize() {
		require_once ROOT_PATH.DS.'common'.DS.'classes'.DS.'autoloader.php';

		\common\classes\AutoLoader::init_autoload();

        require_once ROOT_PATH.DS.'build'.DS.'system'.DS.'system.php';
        require_once ROOT_PATH.DS.'build'.DS.'system'.DS.'handler'.DS.'exceptionhandler.php';

        require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'session.php';
        require_once ROOT_PATH.DS.'build'.DS.'session'.DS.'mappers'.DS.'sessionmapper.php';

        require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'tools.php';
        require_once ROOT_PATH.DS.'build'.DS.'tools'.DS.'mappers'.DS.'templatesmapper.php';

        require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'user.php';
        require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'mappers'.DS.'usermapper.php';
        require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'models'.DS.'usermodel.php';

        /**
         * @var $system System
         */
        $system = Application::get_class(System::class);
        $system->initialize();

        /**
         * @var $configuration Configuration
         */
        $configuration = Application::get_class(Configuration::class);
		defined('CURRENT_LANG') or define('CURRENT_LANG', $configuration->language);

        $starter_autoload = new ReflectionMethod(Starter::class, 'register_autoload');
        $starter_autoload->setAccessible(true);
        $starter_autoload->invoke(Application::get_class(Starter::class));

        static::init_test_tables();
	}

	private static function init_test_tables() {
		$adapter = new \common\adapters\MysqlAdapter('');

		$sql = '
            CREATE TABLE IF NOT EXISTS tests_table (
              `id` serial primary key
              , `field1` varchar(255) not null default ""
              , `field2` bigint not null default 0
              , `field3` enum("val1", "val2", "val3") default "val1"
            )
        ';
		$adapter->execute($sql);

        $sql = '
            CREATE TABLE IF NOT EXISTS tests_table2 (
              `id` serial primary key
              , `field1` varchar(255) not null default ""
              , `field2` bigint not null default 0
              , `field3` enum("val1", "val2", "val3") default "val1"
            )
        ';
        $adapter->execute($sql);

        $roles = [
            User::credentials_admin,
            User::credentials_admin,
            User::credentials_admin,
            User::credentials_user,
            User::credentials_user,
            User::credentials_user,
            User::credentials_super_admin,
            User::credentials_super_admin,
            User::credentials_super_admin,
        ];

        /**
         * @var $user User
         */
        $user = Application::get_class(User::class);
        $mapper = $user->get_mapper();
        for($i=0; $i<9; $i++) {
            $mapper->save(new \User\models\UserModel([
                'login' => uniqid('test', true),
                'credentials' => $roles[$i]
            ]));
        }
	}
}

TestsEnv::initialize();