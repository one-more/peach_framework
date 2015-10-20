<?php

require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'auth'.DS.'userauth.php';

/**
 * Class UserAuthTest
 *
 * @method bool assertTrue($condition)
 * @method bool assertFalse($condition)
 * @method bool assertNull($var)
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
        $user = Application::get_class('User');
        $this->auth = $user->get_auth();
    }

    /**
     * @covers \User\auth\UserAuth::login
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_login() {
        $this->assertFalse($this->auth->login(null,null,null));

        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $this->assertTrue($this->auth->login($model->login, $model->password, true));

        $model = Application::get_class('\User\models\UserModel');
        $model->login = uniqid('test_', true);
        $model->password =  uniqid('', true);
        $mapper->save($model);

        $this->assertTrue($this->auth->login($model->login, $model->password));

        $model->password = uniqid('password', true);
        $this->assertFalse($this->auth->login($model->login, $model->password));
    }

    /**
     * @covers \User\auth\UserAuth::log_out
     */
    public function test_log_out() {
        $this->assertTrue($this->auth->log_out());
    }
}
