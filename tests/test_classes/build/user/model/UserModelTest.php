<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'models'.DS.'usermodel.php';

use common\classes\Application;
use \User\models\UserModel;
/**
 * Class UserModelTest
 *
 */
class UserModelTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $model \User\models\UserModel
     */
    private $model;

    public function setUp() {
        $this->model = Application::get_class(UserModel::class);
    }

    /**
     * @covers \User\models\UserModel::__construct
     */
    public function test_construct() {
        new UserModel;
    }

    /**
     * @covers \User\models\UserModel::is_guest
     * @expectedException PHPUnit_Framework_Error
     */
    public function test_is_guest() {
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
        self::assertTrue($model->is_guest());
        $auth = $user->get_auth();
        self::assertTrue($auth->login($model->login, $model->password));
        self::assertFalse($model->is_guest());
        $auth->log_out();
        self::assertTrue($auth->login($model->login, $model->password, true));
        self::assertFalse($model->is_guest());
        $auth->log_out();
    }

    /**
     * @covers \User\models\UserModel::is_admin
     */
    public function test_is_admin() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_admin]
        ])->one();
        if(!$model) {
            $model = $mapper->find_where([
                'credentials' => ['=', User::credentials_super_admin]
            ])->one();
        }
        self::assertTrue($model->is_admin());
    }

    /**
     * @covers \User\models\UserModel::is_super_admin
     */
    public function test_is_super_admin() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $mapper = $user->get_mapper();
        /**
         * @var $model \User\models\UserModel
         */
        $model = $mapper->find_where([
            'credentials' => ['=', User::credentials_super_admin]
        ])->one();
        self::assertTrue($model->is_super_admin());
    }
}
