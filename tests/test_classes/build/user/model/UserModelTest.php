<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'model'.DS.'usermodel.php';

/**
 * Class UserModelTest
 *
 * @method bool assertTrue($cond)
 * @method bool assertFalse($cond)
 * @method bool assertNull($var)
 * @method bool assertCount($a,$b)
 * @method bool assertGreaterThan($a,$b)
 * @method bool assertEquals($a,$b)
 */
class UserModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $model \User\model\UserModel
     */
    private $model;

    public function setUp() {
        $this->model = Application::get_class('\User\model\UserModel');
    }

    /**
     * @covers \User\model\UserModel::__construct
     */
    public function test_construct() {
        new \User\model\UserModel;
    }

    /**
     * @covers \User\model\UserModel::is_guest
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_is_guest() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $this->assertTrue($model->is_guest());
        $auth = $user->get_auth();
        $this->assertTrue($auth->login($model->login, $model->password));
        $this->assertFalse($model->is_guest());
        $auth->log_out();
        $this->assertTrue($auth->login($model->login, $model->password, true));
        $this->assertFalse($model->is_guest());
        $auth->log_out();
    }

    /**
     * @covers \User\model\UserModel::is_admin
     */
    public function test_is_admin() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_admin]
        ])->one();
        if(!$model) {
            $model = $mapper->find_where([
                'credentials' => ['=', User::credentials_super_admin]
            ])->one();
        }
        $this->assertTrue($model->is_admin());
    }

    /**
     * @covers \User\model\UserModel::is_super_admin
     */
    public function test_is_super_admin() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\model\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_super_admin]
        ])->one();
        $this->assertTrue($model->is_super_admin());
    }
}
