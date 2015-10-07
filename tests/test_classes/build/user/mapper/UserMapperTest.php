<?php
require_once ROOT_PATH.DS.'build'.DS.'user'.DS.'mapper'.DS.'usermapper.php';

/**
 * Class UserMapperTest
 *
 * @method bool assertTrue($condition)
 * @method bool assertFalse($condition)
 * @method bool assertNull($var)
 * @method bool assertCount($count, $array)
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

    /**
     * @covers User\Mapper\UserMapper::validate
     */
    public function test_validate() {
        $method = new  ReflectionMethod($this->mapper, 'validate');
        $method->setAccessible(true);

        $model = new \User\model\UserModel();
        $this->assertFalse($method->invoke($this->mapper, $model->to_array()));

        $model->login = uniqid('test', true);
        $this->assertTrue($method->invoke($this->mapper, $model->to_array()));

        $model->login = uniqid('test', true);
        $this->assertTrue($method->invoke($this->mapper, $model->to_array()));

        /**
         * @var $exited_model \User\model\UserModel
         */
        $exited_model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $model->login = $exited_model->login;
        $this->assertFalse($method->invoke($this->mapper, $model->to_array()));
    }

    /**
     * @covers User\Mapper\UserMapper::insert
     */
    public function test_insert() {
        $model = new \User\model\UserModel();
        $model->login = uniqid('test', true);

        $method = new ReflectionMethod($this->mapper, 'insert');
        $method->setAccessible(true);
        $method->invoke($this->mapper, $model->to_array());

        $this->assertTrue($this->mapper->find_where(['login' => ['=', $model->login]])->one()->get_id() > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::update
     */
    public function test_update() {
        /**
         * @var $model \User\model\UserModel
         */
        $model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $model->login = uniqid('test', true);

        $method = new ReflectionMethod($this->mapper, 'update');
        $method->setAccessible(true);
        $method->invoke($this->mapper, $model->to_array(), $model->id);

        $this->assertTrue($this->mapper->find_where(['login' => ['=', $model->login]])->one()->get_id() > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::find_by_id
     */
    public function test_find_by_id() {
        $this->assertTrue($this->mapper->find_by_id(1)->id > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::find_by_sql
     */
    public function test_find_by_sql() {
        $this->assertTrue($this->mapper->find_by_sql('SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1')->one()->get_id() > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::find_where
     */
    public function test_find_where() {
        $this->assertTrue($this->mapper->find_where(['credentials' => ['=', User::credentials_user]])->one()->get_id() > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::delete
     */
    public function test_delete() {
        /**
         * @var $model \User\model\UserModel
         */
        $model = $this->mapper->find_by_sql('SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1')->one();
        $this->mapper->delete($model);
        $this->assertFalse($this->mapper->find_by_id($model->id)->id > 0);
    }

    /**
     * @covers User\Mapper\UserMapper::get_page
     */
    public function test_get_page() {
        $this->assertCount(5, $this->mapper->get_page(1, 5));
    }
}
