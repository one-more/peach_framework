<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'mapper'.DS.'usermapper.php';

/**
 * Class UserMapperTest
 *
 * @method bool assertTrue($condition)
 * @method bool assertFalse($condition)
 * @method bool assertNull($var)
 */
class UserMapperTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $mapper \User\Mapper\UserMapper
     */
    private $mapper;

    public function setUp() {
        /**
         * @var $user User
         */
        $user = Application::get_class('User');
        $this->mapper = $user->get_mapper();
    }

    /**
     * @covers User\Mapper\UserMapper::__construct
     */
    public function test_construct() {
        new \User\Mapper\UserMapper();
    }

    /**
     * @covers User\Mapper\UserMapper::get_adapter
     */
    public function get_adapter() {
        $method = new ReflectionMethod($this->mapper, 'get_adapter');
        $method->setAccessible(true);
        $this->assertTrue($method->invoke($this->mapper) instanceof MysqlAdapter);
    }

    /**
     * @covers User\Mapper\UserMapper::save
     */
    public function test_save() {
        $model = new \User\model\UserModel();
        $this->assertFalse($this->mapper->save($model));

        $model->login = uniqid('test', true);
        $this->assertTrue($this->mapper->save($model));

        $model->login = uniqid('test', true);
        $this->assertTrue($this->mapper->save($model));

        /**
         * @var $exited_model \User\model\UserModel
         */
        $exited_model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $model->login = $exited_model->login;
        $this->assertFalse($this->mapper->save($model));
    }
}
