<?php

use common\classes\Application;
/**
 * Class UserMapperTest
 *
 */
class UserMapperTest extends PHPUnit_Framework_TestCase {

    /**
     * @var $mapper \User\mappers\UserMapper
     */
    private $mapper;

    public function setUp() {
        /**
         * @var $user User
         */
        $user = Application::get_class(User::class);
        $this->mapper = $user->get_mapper();
    }

    /**
     * @covers User\mappers\UserMapper::__construct
     */
    public function test_construct() {
        new \User\mappers\UserMapper();
    }

    /**
     * @covers User\mappers\UserMapper::get_adapter
     */
    public function test_get_adapter() {
        $method = new ReflectionMethod($this->mapper, 'get_adapter');
        $method->setAccessible(true);
        self::assertTrue($method->invoke($this->mapper) instanceof \common\adapters\MysqlAdapter);
    }

    /**
     * @covers User\mappers\UserMapper::save
     * @covers User\mappers\UserMapper::validate
     */
    public function test_save() {
        $model = new \User\models\UserModel();
        self::assertFalse($this->mapper->save($model));

        $model->login = uniqid('test', true);
        $model->password = str_pad('', 6, mt_rand(1, 300));
        self::assertTrue($this->mapper->save($model));

        $model->login = uniqid('test', true);
        self::assertTrue($this->mapper->save($model));

        /**
         * @var $exited_model \User\models\UserModel
         */
        $exited_model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $model->login = $exited_model->login;
        self::assertFalse($this->mapper->save($model));
    }

    /**
     * @covers User\mappers\UserMapper::validate
     */
    public function test_validate() {
        $method = new  ReflectionMethod($this->mapper, 'validate');
        $method->setAccessible(true);

        $model = new \User\models\UserModel();
        self::assertFalse($method->invoke($this->mapper, $model->to_array()));

        $model->login = uniqid('test', true);
        self::assertTrue((bool)$method->invoke($this->mapper, $model->to_array()));

        $model->login = uniqid('test', true);
        self::assertTrue((bool)$method->invoke($this->mapper, $model->to_array()));

        /**
         * @var $exited_model \User\models\UserModel
         */
        $exited_model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $old_fields = $model->to_array();
        $model->login = $exited_model->login;
        self::assertFalse($method->invoke($this->mapper, $model->to_array()), $old_fields);
        self::assertFalse($method->invoke($this->mapper, $model->to_array()));

        $old_fields = $exited_model->to_array();
        $exited_model->password = uniqid('', true);
        self::assertTrue((bool)$method->invoke($this->mapper, $exited_model->to_array(), $old_fields));
    }

    /**
     * @covers User\mappers\UserMapper::insert
     */
    public function test_insert() {
        $model = new \User\models\UserModel();
        $model->login = uniqid('test', true);

        $method = new ReflectionMethod($this->mapper, 'insert');
        $method->setAccessible(true);
        $method->invoke($this->mapper, $model->to_array());

        self::assertTrue($this->mapper->find_where([
                'login' => ['=', $model->login]
            ])->one()->get_id() > 0);
    }

    /**
     * @covers User\mappers\UserMapper::update
     */
    public function test_update() {
        /**
         * @var $model \User\models\UserModel
         */
        $model = $this->mapper->find_where([
            'credentials' => ['=', User::credentials_user]
        ])->one();
        $model->login = uniqid('test', true);

        $method = new ReflectionMethod($this->mapper, 'update');
        $method->setAccessible(true);
        $method->invoke($this->mapper, $model->to_array(), $model->id);

        self::assertTrue($this->mapper->find_where(['login' => ['=', $model->login]])->one()->get_id() > 0);
    }

    /**
     * @covers User\mappers\UserMapper::find_by_id
     */
    public function test_find_by_id() {
        $adapter = new \common\adapters\MysqlAdapter('users');
        $id = $adapter
            ->select(['id'])
            ->where([
                'deleted' => ['=', 0]
            ])
            ->limit(1)
            ->execute()
            ->get_result();
        self::assertTrue($this->mapper->find_by_id($id)->id > 0);
    }

    /**
     * @covers User\mappers\UserMapper::find_by_sql
     */
    public function test_find_by_sql() {
        $sql = 'SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1';
        self::assertTrue($this->mapper->find_by_sql($sql)->one()->get_id() > 0);
    }

    /**
     * @covers User\mappers\UserMapper::find_where
     */
    public function test_find_where() {
        self::assertTrue($this->mapper->find_where([
                'credentials' => ['=', User::credentials_user]
            ])->one()->get_id() > 0);
    }

    /**
     * @covers User\mappers\UserMapper::delete
     */
    public function test_delete() {
        $sql = 'SELECT * FROM users WHERE deleted = 0 ORDER BY id DESC LIMIT 1';
        /**
         * @var $model \User\models\UserModel
         */
        $model = $this->mapper->find_by_sql($sql)->one();
        $this->mapper->delete($model);
        self::assertFalse($this->mapper->find_by_id($model->id)->id > 0);
    }

    /**
     * @covers User\mappers\UserMapper::get_page
     */
    public function test_get_page() {
        self::assertCount(5, $this->mapper->get_page(1, 5));
    }

    /**
     * @covers User\mappers\UserMapper::get_paging
     */
    public function test_get_paging() {
        self::assertTrue($this->mapper->get_paging() instanceof \common\models\PagingModel);
    }
}
