<?php

use common\classes\Application;

class UserTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $user User
     */
    private $user;

    public function setUp() {
        $this->user = Application::get_class(User::class);
    }

    /**
     * @covers User::__construct
     */
    public function test_construct() {
        new User();
    }

    /**
     * @covers User::get_identity
     */
    public function test_get_identity() {
        if(!empty($_COOKIE['user'])) {
            unset($_COOKIE['user']);
        }
        /**
         * @var $session Session
         */
        $session = Application::get_class(Session::class);
        $session->unset_var('user');

        $identity = $this->user->get_identity();
        self::assertTrue($identity->is_guest());

        $mapper = $this->user->get_mapper();
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_where([
            'password' => ['=', '']
        ])->one();
        $auth = $this->user->get_auth();
        self::assertTrue($auth->login($model->login, $model->password));

        $identity = $this->user->get_identity();
        self::assertFalse($identity->is_guest());

        self::assertTrue($auth->login($model->login, $model->password, true));
        $identity = $this->user->get_identity();
        self::assertFalse($identity->is_guest());
    }

    /**
     * @covers User::get_auth
     */
    public function test_get_auth() {
        $this->user->get_auth();
    }

    /**
     * @covers User::get_mapper
     */
    public function test_get_mapper() {
        self::assertTrue($this->user->get_mapper() instanceof \User\mappers\UserMapper);
    }
}