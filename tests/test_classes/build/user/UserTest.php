<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'user.php';

/**
 * Class UserTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 * @method bool assertNull($var)
 * @method bool assertEquals($a, $b)
 * @method bool assertInternalType($a, $b)
 */
class UserTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $user User
     */
    private $user;

    public function setUp() {
        $this->user = Application::get_class('User');
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
        $identity = $this->user->get_identity();
        $this->assertTrue($identity->is_guest());

        $mapper = $this->user->get_mapper();
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['', User::credentials_user]
        ])->one();
        $auth = $this->user->get_auth();
        $this->assertTrue($auth->login($model->login, $model->password));

        $identity = $this->user->get_identity();
        $this->assertFalse($identity->is_guest());

        $this->assertTrue($auth->login($model->login, $model->password, true));
        $identity = $this->user->get_identity();
        $this->assertFalse($identity->is_guest());
    }

    /**
     * @covers User::get_auth
     */
    public function test_get_auth() {
        $this->assertTrue($this->user->get_auth() instanceof \User\auth\UserAuth);
    }

    /**
     * @covers User::get_mapper
     */
    public function test_get_mapper() {
        $this->assertTrue($this->user->get_mapper() instanceof \User\Mapper\UserMapper);
    }
}