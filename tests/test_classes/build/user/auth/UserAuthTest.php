<?php

require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'auth'.DS.'userauth.php';

use \common\classes\Application;
/**
 * Class UserAuthTest
 *
 */
class UserAuthTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $auth \User\auth\UserAuth
     */
    private $auth;

    public function setUp() {
        /**
         * @var $user User
         */
        $user = Application::get_class(User::class);
        $this->auth = $user->get_auth();
    }

    /**
     * @covers \User\auth\UserAuth::login
     */
    public function test_login() {
        self::assertFalse($this->auth->login(null,null,null));

        /**
         * @var $user User
         */
        $user = Application::get_class(User::class);
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_where([
            'password' => ['=', '']
        ])->one();
        self::assertTrue($this->auth->login($model->login, $model->password, true));

        $model = Application::get_class('\User\models\UserModel');
        $model->login = uniqid('test_', true);
        $model->password =  uniqid('', true);
        $mapper->save($model);

        self::assertTrue($this->auth->login($model->login, $model->password));

        $model->password = uniqid('password', true);
        self::assertFalse($this->auth->login($model->login, $model->password));

        $model = $mapper->find_where([
            'remember_hash' => ['=', '']
        ])->one();
        $model->remember_hash = '';
        $model->password = '';
        $mapper->save($model);
        self::assertTrue($this->auth->login($model->login, $model->password));
    }

    /**
     * @covers \User\auth\UserAuth::log_out
     */
    public function test_log_out() {
        /**
         * @var $user User
         */
        $user = Application::get_class(User::class);
        $mapper = $user->get_mapper();
        $sql = 'select * from users WHERE deleted = 0 AND password = "" ORDER BY id DESC LIMIT 1';
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_by_sql($sql)->one();

        self::assertTrue($this->auth->login($model->login, $model->password));
        self::assertTrue($this->auth->log_out());

        self::assertTrue($this->auth->login($model->login, $model->password, true));
        self::assertTrue($this->auth->log_out());

        self::assertFalse($this->auth->log_out());
    }
}
